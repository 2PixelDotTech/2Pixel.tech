<?php
/**
 * Build settings. Copy this file to config/config.php (git-ignored) and fill in real values.
 * These are used by build.php when it generates the static site. Every value can also be set
 * as an environment variable with the same name.
 *
 * Nothing secret belongs here: the Turnstile SECRET key and the Resend API key are Worker
 * secrets (npx wrangler secret put ...), and email addresses for the form are in wrangler.jsonc.
 */
return [
    // Live domain, no trailing slash. Used for canonical URLs, Open Graph, robots.txt and sitemap.xml.
    'SITE_URL' => 'https://2pixels.tech',

    // Public email shown on the site.
    'CONTACT_EMAIL' => 'info@2pixels.tech',

    // Your real Cloudflare Turnstile SITE key (public, starts with 0x). Used only by `npm run deploy`;
    // local builds automatically use Cloudflare's test key so the form works on localhost.
    'TURNSTILE_SITE_KEY' => 'PASTE_YOUR_SITE_KEY_HERE',

    // Shown in the Privacy Policy (POPIA section 18 requires the responsible party's name and address).
    // The Information Officer must also be registered with the Information Regulator.
    'INFORMATION_OFFICER' => 'Wanga Nembahe',
    'BUSINESS_ADDRESS'    => '', // a physical or postal address, not an email

    // Set to false for staging/test builds so search engines never index them.
    'ALLOW_INDEXING' => true,
];
