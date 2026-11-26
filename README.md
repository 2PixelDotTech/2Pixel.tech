# 2Pixel.tech: Two Pixel website

Marketing website for **Two Pixel** (https://2pixels.tech), custom software for growing businesses worldwide.

The site is built from the Figma design (file `ihU77qnMLeyqUXemwwpSjd`, frame `1:3`) with plain **HTML5, CSS3, vanilla JavaScript and PHP 8** templates. It is hosted for free on **Cloudflare**:

- **The pages are static.** `build.php` renders the PHP templates into plain HTML on your computer. PHP never runs on the server.
- **Cloudflare serves the site** (Workers static assets) from its global network.
- **One small Worker handles the contact form.** It uses **Turnstile** to block spam, **Resend** to send the email, and **Workers KV** to keep a backup of each enquiry.

There are no frameworks and no database. Wrangler (Cloudflare's command-line tool) is the only dependency.

**First-time setup and going live:** see [docs/CLOUDFLARE-SETUP.md](docs/CLOUDFLARE-SETUP.md).

## Folder structure

```text
/
├── index.php                          Home page (the full one-page design)
├── operations-management-platform.php Case-study page ("View project detail")
├── privacy-policy.php                 Privacy Policy (POPIA)
├── 404.php                            "Page not found" page
├── build.php                          Renders the pages to dist/, plus robots.txt and sitemap.xml
├── includes/                          Shared templates
│   ├── bootstrap.php                  Config and helpers (e(), asset(), icon()…)
│   ├── head.php                       <head>: title, meta, canonical, Open Graph, JSON-LD
│   ├── header.php / footer.php        Navigation and footer
│   └── dashboard-mockup.php           The HTML/CSS "Operations Hub" illustration
├── assets/                            CSS, JS, images (copied to dist/assets)
├── cloudflare/_headers                Security headers, CSP and caching (copied to dist/)
├── worker/index.js                    Contact-form API: POST /api/contact
├── wrangler.jsonc                     Cloudflare configuration (Worker, assets, vars, KV, domain)
├── config/config.example.php          Build settings. Copy to config/config.php (git-ignored)
├── .dev.vars.example                  Local Worker secrets. Copy to .dev.vars (git-ignored)
├── package.json                       npm scripts: build, dev, deploy
└── docs/                              Cloudflare setup guide, SEO handover
```

`dist/`, `node_modules/`, `.wrangler/`, `.dev.vars` and `config/config.php` are git-ignored.

## Run it locally

You need **PHP 8.1+** (this Mac has PHP 8.2 through XAMPP) and **Node.js 20+**.

```bash
npm install
```

```bash
cp .dev.vars.example .dev.vars
```

```bash
npm run dev
```

Open http://localhost:8787. This builds the site and runs it exactly as Cloudflare will, contact form included.

Locally, Turnstile uses Cloudflare's **test keys**, which always pass and show a "testing only" banner. Unless you add a Resend key to `.dev.vars`, the form reports that it couldn't deliver the message. That's expected: nothing is configured to receive it.

## Settings

| Where | Setting | Purpose |
|---|---|---|
| `config/config.php` | `SITE_URL` | `https://2pixels.tech`. Used for canonical URLs, Open Graph, robots.txt and the sitemap |
| | `CONTACT_EMAIL` | Address shown on the site (`info@2pixels.tech`) |
| | `TURNSTILE_SITE_KEY` | Public Turnstile key. `npm run deploy` refuses to deploy with the test key |
| | `INFORMATION_OFFICER`, `BUSINESS_ADDRESS` | Shown in the Privacy Policy (required by POPIA) |
| | `ALLOW_INDEXING` | `false` for staging builds (adds noindex; robots.txt blocks all) |
| `wrangler.jsonc` → `vars` | `MAIL_TO`, `MAIL_FROM`, `CONTACT_EMAIL` | Where enquiries are emailed and who they come from |
| Cloudflare secrets | `TURNSTILE_SECRET_KEY`, `RESEND_API_KEY` | Set with `npx wrangler secret put NAME`. Never stored in files |
| `wrangler.jsonc` → `kv_namespaces` | `ENQUIRIES` | Backup store; enquiries are kept for 12 months |

Settings in `config/config.php` can also be supplied as environment variables when building.

## How the contact form works

1. `main.js` validates the fields in the browser. It loads Turnstile only when the visitor scrolls near the form, then sends the form to `/api/contact` with `fetch()`.
2. `worker/index.js` checks the request:
   - it rejects other websites (Origin check) and oversized requests;
   - it quietly drops spam caught by the hidden **honeypot** field or a **minimum fill time** of 3 seconds;
   - it validates every field again;
   - it verifies the **Turnstile** token with Cloudflare.
3. It emails the enquiry through **Resend** as plain text, with the visitor's address as *Reply-To*. It also stores a copy in **KV** for 12 months.
4. It reports success only if the email was accepted or the copy was stored. Otherwise the visitor is asked to email you directly.

**Buttons that prefill the form:** "Discuss this service" and the industry cards fill in the enquiry topic. "Book a Call" selects *Video call*. "Tell Us About Your Process" focuses the message box.

The form needs JavaScript, because Turnstile does. Visitors without JavaScript see a message with the email address instead.

## Deploying

```bash
npm run deploy
```

This builds with production checks and uploads everything to Cloudflare. The first time, follow [docs/CLOUDFLARE-SETUP.md](docs/CLOUDFLARE-SETUP.md).

**Adding a page:**
1. Create `new-page.php`. Copy the structure of `privacy-policy.php`, with a unique `$page` title, description and path.
2. Add it to `$pages` in `build.php`.
3. Link to it from the navigation or footer.
