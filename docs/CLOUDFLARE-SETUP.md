# Putting 2pixels.tech live on Cloudflare

This is the one-time setup. Everything here is free. Do the parts in order; each one ends with how to check it worked.

Where it says *run*, type the command in Terminal inside the project folder (`/Users/wanga/2Pixel.tech`).

**How the site works on Cloudflare**

| Part | What it does |
|---|---|
| `php build.php` | Renders the PHP templates into plain HTML in `dist/` on your Mac |
| Cloudflare Workers (static assets) | Serves `dist/` from Cloudflare's global network |
| `worker/index.js` | Handles the contact form (`POST /api/contact`) |
| Turnstile | Cloudflare's free spam check on the form |
| Workers KV | Free storage that keeps a backup copy of every enquiry for 12 months |
| Resend | Sends you an email for each enquiry (add it when your email is ready) |
| Email Routing | Forwards mail sent to info@2pixels.tech to an inbox you already use |

---

## Part 1: Move your domain's DNS to Cloudflare (about 15 minutes, then waiting)

Your domain stays registered with your current registrar. Only the DNS (the address book for the domain) moves to Cloudflare.

1. Create a free account at https://dash.cloudflare.com/sign-up.
2. Choose **Add a domain**, enter `2pixels.tech`, and pick the **Free** plan.
3. Cloudflare scans your existing DNS records. If you're not using the domain for anything yet, there may be none, which is fine.
4. Cloudflare shows you **two nameservers** (for example `xxx.ns.cloudflare.com`). Keep that page open.
5. Log in to the registrar where you bought `2pixels.tech`. Find **Nameservers** (sometimes under *DNS* or *Manage domain*), choose **custom nameservers**, and replace the existing ones with Cloudflare's two.
6. Back in Cloudflare, click **Check nameservers**.

**Check:** Cloudflare emails you when the domain is **Active**. This usually takes under an hour, but can take up to 24 hours.

## Part 2: Forward info@2pixels.tech to your inbox (5 minutes)

Do this only if you **don't** already have a mailbox provider (Google Workspace, Zoho, etc.) receiving mail for 2pixels.tech, because Email Routing takes over the domain's incoming mail.

1. In Cloudflare, open your domain, then **Email → Email Routing**, and click **Get started** or **Enable**.
2. Let Cloudflare add the DNS records it suggests.
3. Under **Routing rules**, create the custom address `info`. Set the action to **Send to an email**, and enter the inbox you want enquiries to reach. Add `wanga` the same way if you want that address too.
4. Cloudflare emails that inbox a verification link. Click it.

**Check:** send an email to info@2pixels.tech from another account. It should arrive in your inbox.

Email Routing only *receives*. Replying *as* info@2pixels.tech needs a mailbox provider, or Gmail's "Send mail as" with an SMTP service. You can set that up later; it doesn't affect the website.

## Part 3: Create the Turnstile spam check (5 minutes)

1. In Cloudflare, go to **Turnstile** (left menu, at account level) and click **Add widget**.
2. Name: `2pixels.tech contact form`. Hostname: `2pixels.tech`. Widget mode: **Managed**.
3. Click **Create**. You get two keys:
   - **Site key** (public). It goes into `config/config.php` (Part 5).
   - **Secret key** (private). It goes into Cloudflare as a secret (Part 6). Never paste it into a file.

## Part 4: Connect your Mac to your Cloudflare account (2 minutes)

Run:

```bash
npx wrangler login
```

A browser window opens. Click **Allow**.

**Check:** `npx wrangler whoami` shows your account.

## Part 5: Fill in the site settings

Run:

```bash
cp config/config.example.php config/config.php
```

Open `config/config.php` and set:

- `TURNSTILE_SITE_KEY`: the **site key** from Part 3.
- `BUSINESS_ADDRESS`: required by POPIA for the Privacy Policy.
- `INFORMATION_OFFICER`: confirm the name.

`config/config.php` is never committed to git.

## Part 6: Add the secret key and the enquiry backup

1. Store the Turnstile **secret key** in Cloudflare:

   ```bash
   npx wrangler secret put TURNSTILE_SECRET_KEY
   ```

   Paste the key when asked. If it asks whether to create the Worker `two-pixel`, answer **yes**.

2. Create the backup store for enquiries:

   ```bash
   npx wrangler kv namespace create ENQUIRIES
   ```

   It prints an `id`. Open `wrangler.jsonc`, uncomment the `kv_namespaces` block, and paste the id in place of `PASTE_THE_ID_HERE`.

Until Resend is set up (Part 9), this backup is where enquiries arrive, so **don't skip it**. Without Resend *or* KV, the form tells visitors to email you instead.

## Part 7: Connect the domain and go live

1. In `wrangler.jsonc`, uncomment the `routes` block so it reads:

   ```jsonc
   "routes": [
     { "pattern": "2pixels.tech", "custom_domain": true },
     { "pattern": "www.2pixels.tech", "custom_domain": true }
   ],
   ```

   You'll need to add the second line, for `www`.

2. Deploy:

   ```bash
   npm run deploy
   ```

   This builds the site and uploads it. It refuses to run while the Turnstile test key is still in `config/config.php`.

3. In Cloudflare, go to **Rules → Redirect Rules → Create rule**, choose the template **Redirect from WWW to root**, and deploy it. Visitors to `www.2pixels.tech` are then sent to `2pixels.tech`, matching the canonical URLs.

4. In **SSL/TLS → Edge Certificates**, make sure **Always Use HTTPS** is on.

**Check:** go through the checklist at the end of this guide.

## Part 8: Read enquiries (until email is set up)

- **Dashboard:** go to **Storage & Databases → KV → ENQUIRIES**. Each entry is one enquiry.
- **Terminal:**

  ```bash
  npx wrangler kv key list --binding ENQUIRIES --remote
  ```

  ```bash
  npx wrangler kv key get --binding ENQUIRIES --remote "PASTE_A_KEY_NAME"
  ```

Entries delete themselves after 12 months, as the Privacy Policy says.

## Part 9: Email notifications with Resend (when you're ready)

1. Sign up at https://resend.com (free plan). Check its current sending limits; at the time of writing it allowed about 100 emails a day.
2. Go to **Domains → Add domain** and enter `2pixels.tech`. Resend shows a few DNS records (DKIM, SPF, and an MX record for a `send` subdomain). It may offer to add them to Cloudflare automatically. Otherwise add them under **DNS → Records** in Cloudflare. They don't interfere with Email Routing.
3. When the domain shows **Verified**, go to **API Keys → Create API key** with **Sending access** only, and copy it.
4. Run:

   ```bash
   npx wrangler secret put RESEND_API_KEY
   ```

   Paste the key when asked. Then run:

   ```bash
   npm run deploy
   ```

Enquiries are emailed **to** `MAIL_TO` (info@2pixels.tech) **from** `MAIL_FROM` (website@2pixels.tech), with the visitor's address as *Reply-To*. Both are set in `wrangler.jsonc`. `website@` doesn't need to be a real mailbox.

**Check:** submit the form. The email arrives, and an entry also appears in KV.

## Optional extras

- **Stop the `*.workers.dev` copy of the site:** once the domain works, add `"workers_dev": false` to `wrangler.jsonc` and deploy again.
- **Extra spam protection:** if spam ever gets past Turnstile, go to **Security → WAF → Rate limiting rules** and add a free rule for URI path `/api/contact`, limiting each IP to a few requests.
- **Errors:** **Workers & Pages → two-pixel → Logs** shows any errors from the contact form, such as a rejected Resend key.

## Updating the site later

Edit the PHP templates, CSS or JS. Preview locally with:

```bash
npm run dev
```

The preview runs at http://localhost:8787. When you're happy, run:

```bash
npm run deploy
```

## Go-live checklist

- [ ] `https://2pixels.tech/` loads; `http://` and `www.` redirect to it
- [ ] `/operations-management-platform` and `/privacy-policy` load; `/anything-else` shows the 404 page
- [ ] `/robots.txt` and `/sitemap.xml` show `https://2pixels.tech`
- [ ] View source: `<meta name="robots" content="index, follow…">`, and the Turnstile `data-sitekey` is **your** key, not `1x0000…`
- [ ] The form shows the Turnstile check (no "testing only" banner)
- [ ] Submitting the form shows the thank-you message, and the enquiry appears in KV and/or your inbox
- [ ] Email to info@2pixels.tech reaches your inbox
- [ ] Privacy Policy shows your business address
- [ ] Google Search Console: add `2pixels.tech` as a *Domain property* (Cloudflare can add the verification record for you) and submit `https://2pixels.tech/sitemap.xml`
- [ ] PageSpeed Insights and the Rich Results Test on the home page
