<?php
/**
 * Static site build for Cloudflare.
 *
 *   php build.php          (or: npm run build)
 *
 * Renders every PHP page into plain HTML in dist/, writes robots.txt and sitemap.xml,
 * and copies assets plus the Cloudflare _headers file. Cloudflare serves dist/ as-is;
 * the contact form is handled by the Worker in worker/index.js.
 *
 * Settings come from config/config.php or environment variables (see config/config.example.php).
 */
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    exit('Run this from the command line: php build.php');
}

require __DIR__ . '/includes/bootstrap.php';

$dist = ROOT_DIR . '/dist';
$production = in_array('--production', $argv, true);

if ($production && TURNSTILE_SITE_KEY === '1x00000000000000000000AA') {
    fwrite(STDERR, "Stopped: TURNSTILE_SITE_KEY is still Cloudflare's test key. Add your real site key to config/config.php.\n");
    exit(1);
}
if ($production && !ALLOW_INDEXING) {
    fwrite(STDERR, "Stopped: ALLOW_INDEXING is false, so the live site would be hidden from search engines.\n");
    exit(1);
}

/** Public pages: URL path => [source template, output file, include in sitemap?] */
$pages = [
    '/'                               => ['index.php', 'index.html', true],
    '/operations-management-platform' => ['operations-management-platform.php', 'operations-management-platform.html', true],
    '/privacy-policy'                 => ['privacy-policy.php', 'privacy-policy.html', true],
    '/404'                            => ['404.php', '404.html', false],
];

/* ---------- Helpers ---------- */
function remove_dir(string $dir): void
{
    if (!is_dir($dir)) {
        return;
    }
    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($items as $item) {
        $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
    }
    rmdir($dir);
}

function copy_dir(string $from, string $to): int
{
    $count = 0;
    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($from, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($items as $item) {
        if ($item->getFilename() === '.DS_Store') {
            continue;
        }
        $target = $to . '/' . substr($item->getPathname(), strlen($from) + 1);
        if ($item->isDir()) {
            @mkdir($target, 0755, true);
        } else {
            @mkdir(dirname($target), 0755, true);
            copy($item->getPathname(), $target);
            $count++;
        }
    }
    return $count;
}

/** Render a template in its own scope and return the HTML. */
function render_page(string $template): string
{
    ob_start();
    try {
        (static function (string $__file): void {
            require $__file;
        })(ROOT_DIR . '/' . $template);
    } catch (Throwable $e) {
        ob_end_clean();
        throw $e;
    }
    return (string) ob_get_clean();
}

/* ---------- Build ---------- */
remove_dir($dist);
mkdir($dist, 0755, true);

foreach ($pages as $path => [$template, $output]) {
    $html = render_page($template);
    if (!str_contains($html, '</html>')) {
        fwrite(STDERR, "Build failed: {$template} did not render a complete page.\n");
        exit(1);
    }
    file_put_contents("{$dist}/{$output}", $html);
    echo sprintf("  page     %-36s -> dist/%s\n", $path, $output);
}

// robots.txt (blocks everything on staging builds)
$robots = ALLOW_INDEXING
    ? "User-agent: *\nAllow: /\nDisallow: /api/\n\nSitemap: " . absolute_url('sitemap.xml') . "\n"
    : "User-agent: *\nDisallow: /\n";
file_put_contents("{$dist}/robots.txt", $robots);
echo "  file     robots.txt\n";

// sitemap.xml (lastmod = the template's last modified date)
$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
    . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($pages as $path => [$template, , $inSitemap]) {
    if (!$inSitemap) {
        continue;
    }
    $xml .= "  <url>\n    <loc>" . e(absolute_url($path)) . "</loc>\n"
        . '    <lastmod>' . date('Y-m-d', (int) filemtime(ROOT_DIR . '/' . $template)) . "</lastmod>\n  </url>\n";
}
$xml .= "</urlset>\n";
file_put_contents("{$dist}/sitemap.xml", $xml);
echo "  file     sitemap.xml\n";

// Assets and Cloudflare headers
$assetCount = copy_dir(ROOT_DIR . '/assets', "{$dist}/assets");
echo "  assets   {$assetCount} files\n";
copy(ROOT_DIR . '/cloudflare/_headers', "{$dist}/_headers");
echo "  file     _headers\n";

echo "\nBuilt " . count($pages) . " pages into dist/ for " . SITE_URL
    . (ALLOW_INDEXING ? '' : ' (noindex staging build)') . "\n";
if (TURNSTILE_SITE_KEY === '1x00000000000000000000AA') {
    echo "Note: using Cloudflare's Turnstile TEST site key (fine locally). Set TURNSTILE_SITE_KEY before deploying.\n";
}
