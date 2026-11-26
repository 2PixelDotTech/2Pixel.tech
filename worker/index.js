/**
 * Two Pixel: Cloudflare Worker.
 *
 * Static pages and assets in dist/ are served by Cloudflare directly. This Worker only runs
 * for requests that don't match a file, and handles one route:
 *
 *   POST /api/contact  ->  validate, check Turnstile, email the enquiry (Resend) and/or store it (KV)
 *
 * Everything else is passed back to the static assets (which serve dist/404.html for unknown pages).
 *
 * Settings (wrangler.jsonc "vars" and secrets; see docs/CLOUDFLARE-SETUP.md):
 *   TURNSTILE_SECRET_KEY  secret   Turnstile secret key (spam check)
 *   RESEND_API_KEY        secret   Resend API key (sends the email)
 *   MAIL_TO               var      Inbox that receives enquiries
 *   MAIL_FROM             var      Sender, on a domain verified in Resend
 *   CONTACT_EMAIL         var      Public address quoted in error messages
 *   ENQUIRIES             KV       Optional backup store of enquiries (kept 12 months)
 */

const MAX_BODY_BYTES = 64 * 1024;
const RETENTION_SECONDS = 365 * 24 * 60 * 60; // matches the Privacy Policy (12 months)
const CONTACT_METHODS = ['email', 'phone', 'whatsapp', 'video'];

export default {
  async fetch(request, env) {
    const url = new URL(request.url);

    if (url.pathname === '/api/contact') {
      if (request.method !== 'POST') {
        return new Response(null, { status: 303, headers: { Location: '/#contact', Allow: 'POST' } });
      }
      try {
        return await handleContact(request, env);
      } catch (err) {
        console.error('[contact] unexpected error', err && err.stack ? err.stack : err);
        return json(env, false, failMessage(env), {}, 500);
      }
    }

    return env.ASSETS.fetch(request);
  },
};

async function handleContact(request, env) {
  // Only accept submissions from our own pages.
  const origin = request.headers.get('Origin');
  if (origin && origin !== new URL(request.url).origin) {
    return json(env, false, 'This form can only be sent from our website.', {}, 403);
  }
  const length = Number(request.headers.get('Content-Length') || 0);
  if (length > MAX_BODY_BYTES) {
    return json(env, false, 'Your message is too long.', {}, 413);
  }

  let form;
  try {
    form = await request.formData();
  } catch {
    return json(env, false, 'We could not read the form. Please try again.', {}, 400);
  }

  const clean = (key, max) => String(form.get(key) ?? '').replace(/\0/g, '').trim().slice(0, max);
  const input = {
    full_name: clean('full_name', 120),
    company: clean('company', 120),
    email: clean('email', 254),
    phone: clean('phone', 40),
    business: clean('business', 200),
    process: clean('process', 3000),
    budget: clean('budget', 100), // free text: any amount, any currency
    contact_method: clean('contact_method', 20),
    interest: clean('interest', 120),
  };

  // Honeypot filled, or form sent impossibly fast: treat as spam but reply politely.
  const started = Number(form.get('form_started') || 0);
  const now = Math.floor(Date.now() / 1000);
  if (clean('website', 200) !== '' || (started > 0 && now - started < 3)) {
    return json(env, true, 'Thanks, your message has been received.');
  }

  // Validation (mirrors the browser checks in assets/js/main.js).
  const errors = {};
  if (input.full_name.length < 2) errors.full_name = 'Please enter your name.';
  if (!/^[^\s@<>()[\]\\,;:"]+@[^\s@<>()[\]\\,;:"]+\.[^\s@<>()[\]\\,;:"]{2,}$/.test(input.email)) {
    errors.email = 'Please enter a valid email address.';
  }
  if (input.phone !== '' && !/^\+?[0-9 ()\-]{7,20}$/.test(input.phone)) {
    errors.phone = 'Please enter a valid phone number.';
  }
  if (input.process.length < 10) {
    errors.process = 'Please tell us a little about the process (at least 10 characters).';
  }
  if (!CONTACT_METHODS.includes(input.contact_method)) input.contact_method = 'email';
  if ((input.contact_method === 'phone' || input.contact_method === 'whatsapp') && input.phone === '') {
    errors.phone = 'Please add a phone number so we can contact you that way.';
  }
  if (Object.keys(errors).length) {
    return json(env, false, 'Please check the highlighted fields.', errors, 422);
  }

  // Turnstile: confirm a person sent this (checked after validation, because each token works once).
  if (env.TURNSTILE_SECRET_KEY) {
    const token = clean('cf-turnstile-response', 2048);
    const passed = token !== '' && (await verifyTurnstile(env.TURNSTILE_SECRET_KEY, token, request));
    if (!passed) {
      return json(env, false, 'We could not confirm you are human. Please complete the check and try again.', {}, 403);
    }
  } else {
    console.warn('[contact] TURNSTILE_SECRET_KEY is not set: spam check skipped');
  }

  const record = { received_at: new Date().toISOString(), ...input };
  let delivered = false;

  // 1) Email through Resend (only when it is configured).
  if (env.RESEND_API_KEY && env.MAIL_TO && env.MAIL_FROM) {
    delivered = await sendEmail(env, input, record.received_at);
  }

  // 2) Backup copy in Workers KV (only when the ENQUIRIES namespace is bound).
  if (env.ENQUIRIES) {
    try {
      const key = `enquiry:${record.received_at}:${crypto.randomUUID()}`;
      await env.ENQUIRIES.put(key, JSON.stringify(record), { expirationTtl: RETENTION_SECONDS });
      delivered = true;
    } catch (err) {
      console.error('[contact] could not store enquiry in KV', err);
    }
  }

  if (!delivered) {
    console.error('[contact] enquiry not delivered: configure RESEND_API_KEY/MAIL_TO/MAIL_FROM or the ENQUIRIES KV namespace');
    return json(env, false, failMessage(env), {}, 500);
  }

  return json(env, true, `Thanks, ${input.full_name}. Your message has been received. A developer will be in touch soon.`);
}

async function verifyTurnstile(secret, token, request) {
  const body = new FormData();
  body.append('secret', secret);
  body.append('response', token);
  const ip = request.headers.get('CF-Connecting-IP');
  if (ip) body.append('remoteip', ip);
  try {
    const res = await fetch('https://challenges.cloudflare.com/turnstile/v0/siteverify', { method: 'POST', body });
    const data = await res.json();
    if (!data.success) console.warn('[contact] Turnstile rejected', data['error-codes']);
    return data.success === true;
  } catch (err) {
    console.error('[contact] Turnstile verification failed', err);
    return false;
  }
}

async function sendEmail(env, input, receivedAt) {
  const lines = [
    'New enquiry from the Two Pixel website',
    '',
    `Name:            ${input.full_name}`,
    `Company:         ${input.company || '-'}`,
    `Email:           ${input.email}`,
    `Phone:           ${input.phone || '-'}`,
    `Business:        ${input.business || '-'}`,
    `Enquiring about: ${input.interest || '-'}`,
    `Budget:          ${input.budget || '-'}`,
    `Contact via:     ${input.contact_method}`,
    '',
    'Process to improve:',
    input.process,
    '',
    `Sent: ${receivedAt}`,
  ];
  try {
    const res = await fetch('https://api.resend.com/emails', {
      method: 'POST',
      headers: { Authorization: `Bearer ${env.RESEND_API_KEY}`, 'Content-Type': 'application/json' },
      body: JSON.stringify({
        from: `Two Pixel Website <${env.MAIL_FROM}>`,
        to: [env.MAIL_TO],
        reply_to: input.email, // validated above
        subject: `Website enquiry: ${input.full_name.replace(/[\r\n]+/g, ' ')}`,
        text: lines.join('\n'), // plain text only: nothing the visitor typed is rendered as HTML
      }),
    });
    if (!res.ok) {
      console.error('[contact] Resend error', res.status, await res.text());
      return false;
    }
    return true;
  } catch (err) {
    console.error('[contact] Resend request failed', err);
    return false;
  }
}

function failMessage(env) {
  const email = env.CONTACT_EMAIL || 'info@2pixels.tech';
  return `Sorry, we could not send your message right now. Please email us at ${email}.`;
}

function json(env, ok, message, errors = {}, status = 200) {
  return new Response(JSON.stringify({ ok, message, errors }), {
    status,
    headers: {
      'Content-Type': 'application/json; charset=utf-8',
      'Cache-Control': 'no-store',
      'X-Content-Type-Options': 'nosniff',
    },
  });
}
