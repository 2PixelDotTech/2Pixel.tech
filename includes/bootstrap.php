<?php
/**
 * Shared setup loaded at the top of every page.
 *
 * The site is static: build.php renders these PHP templates into plain HTML in dist/,
 * which Cloudflare serves. PHP runs only on your computer at build time, never on the server.
 *
 * - Loads configuration (config/config.php, falling back to environment variables)
 * - Provides small helper functions used by the templates
 */

declare(strict_types=1);

define('ROOT_DIR', dirname(__DIR__));

// Never show PHP errors to visitors; log them instead.
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

/* -------------------------------------------------------------------------
 * Configuration
 * ---------------------------------------------------------------------- */
$config = [];
$configFile = ROOT_DIR . '/config/config.php';
if (is_file($configFile)) {
    $loaded = require $configFile;
    if (is_array($loaded)) {
        $config = $loaded;
    }
}

/**
 * Read a config value: config/config.php first, then an environment variable, then the default.
 */
function config(string $key, $default = null)
{
    global $config;
    if (array_key_exists($key, $config) && $config[$key] !== '') {
        return $config[$key];
    }
    $env = getenv($key);
    if ($env !== false && $env !== '') {
        return $env;
    }
    return $default;
}

define('SITE_NAME', 'Two Pixel');
define('SITE_URL', rtrim((string) config('SITE_URL', 'https://2pixels.tech'), '/'));
define('CONTACT_EMAIL', (string) config('CONTACT_EMAIL', 'info@2pixels.tech'));
// Set ALLOW_INDEXING to false on staging copies so search engines don't index them.
define('ALLOW_INDEXING', filter_var(config('ALLOW_INDEXING', true), FILTER_VALIDATE_BOOLEAN));
// Cloudflare Turnstile site key (public; the SECRET key lives only in the Worker).
// Local builds (npm run dev / npm run build) always use Cloudflare's official test key, which pairs
// with the test secret in .dev.vars and works on localhost. Only `npm run deploy` (build.php --production)
// uses your real key from config/config.php, which only works on 2pixels.tech.
define('TURNSTILE_TEST_SITE_KEY', '1x00000000000000000000AA');
define('PRODUCTION_BUILD', PHP_SAPI === 'cli' && in_array('--production', $_SERVER['argv'] ?? [], true));
define('TURNSTILE_SITE_KEY', PRODUCTION_BUILD
    ? (string) config('TURNSTILE_SITE_KEY', TURNSTILE_TEST_SITE_KEY)
    : TURNSTILE_TEST_SITE_KEY);

/* -------------------------------------------------------------------------
 * Helpers
 * ---------------------------------------------------------------------- */

/** Escape text for safe output in HTML. Use for every dynamic value. */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Absolute URL on the live domain (used for canonical, Open Graph, sitemap). */
function absolute_url(string $path = '/'): string
{
    return SITE_URL . '/' . ltrim($path, '/');
}

/** Asset URL with a cache-busting version based on the file's modified time. */
function asset(string $path): string
{
    $file = ROOT_DIR . '/assets/' . ltrim($path, '/');
    $version = is_file($file) ? (string) filemtime($file) : '1';
    return '/assets/' . ltrim($path, '/') . '?v=' . $version;
}

/** Inline SVG icons used across the site (kept in one place so they stay consistent). */
function icon(string $name, int $size = 16): string
{
    $paths = [
        'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'check'    => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
        'chevron'  => '<path d="M6 9l6 6 6-6"/>',
        'menu'     => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'close'    => '<path d="M6 6l12 12M18 6L6 18"/>',
        'window'   => '<rect x="3.5" y="5" width="17" height="14" rx="2"/><path d="M3.5 9.5h17M7.5 13.5h5M7.5 16h3"/>',
        'spark'    => '<circle cx="12" cy="12" r="2.5"/><path d="M12 3v4M12 17v4M3 12h4M17 12h4M5.6 5.6l2.8 2.8M15.6 15.6l2.8 2.8M5.6 18.4l2.8-2.8M15.6 8.4l2.8-2.8"/>',
        'phone'    => '<rect x="7" y="3" width="10" height="18" rx="2"/><path d="M11 17.5h2"/>',
        'stack'    => '<path d="M4 7h4M4 12h4M4 17h4"/><path d="M11 7h9M11 12h9M11 17h9"/>',
        'link'     => '<path d="M10 14a4 4 0 0 0 5.66 0l3-3a4 4 0 0 0-5.66-5.66l-1 1"/><path d="M14 10a4 4 0 0 0-5.66 0l-3 3a4 4 0 0 0 5.66 5.66l1-1"/>',
        'square'   => '<rect x="5" y="5" width="14" height="14" rx="3"/>',
    ];
    $d = $paths[$name] ?? '';
    return '<svg class="icon icon-' . e($name) . '" width="' . $size . '" height="' . $size
        . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
        . $d . '</svg>';
}

/** The Two Pixel logo mark (four squares), as drawn in the Figma design. */
function logo_mark(int $size = 26, bool $onDark = false): string
{
    $a = $onDark ? '#ffffff' : '#0F172A';
    $b = '#3B82F6';
    return '<svg class="logo-mark" width="' . $size . '" height="' . $size . '" viewBox="0 0 26 26" aria-hidden="true" focusable="false">'
        . '<rect x="0" y="0" width="11.5" height="11.5" rx="2.5" fill="' . $a . '"/>'
        . '<rect x="14.5" y="0" width="11.5" height="11.5" rx="2.5" fill="' . $b . '"/>'
        . '<rect x="0" y="14.5" width="11.5" height="11.5" rx="2.5" fill="' . $b . '"/>'
        . '<rect x="14.5" y="14.5" width="11.5" height="11.5" rx="2.5" fill="' . $a . '"/>'
        . '</svg>';
}
