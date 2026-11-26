<?php
/**
 * <head> section shared by all pages.
 *
 * Each page sets $page before including this file:
 *   $page = [
 *     'title'       => 'Unique page title',
 *     'description' => 'Unique meta description (~150 characters)',
 *     'path'        => '/',            // canonical path
 *     'noindex'     => false,          // true for 404 / utility pages
 *     'schema'      => [ ...JSON-LD arrays... ],
 *   ];
 */

$pageTitle       = $page['title'] ?? SITE_NAME;
$pageDescription = $page['description'] ?? '';
$canonical       = absolute_url($page['path'] ?? '/');
$noindex         = !empty($page['noindex']) || !ALLOW_INDEXING;
$ogImage         = absolute_url('assets/images/two-pixel-custom-business-software-og.png');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($pageDescription) ?>">
    <?php if ($noindex): ?>
    <meta name="robots" content="noindex, follow">
    <?php else: ?>
    <meta name="robots" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="<?= e($canonical) ?>">
    <?php endif; ?>
    <meta name="theme-color" content="#0F172A">

    <!-- Open Graph / social sharing -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
    <meta property="og:locale" content="en_GB">
    <meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($pageDescription) ?>">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:image" content="<?= e($ogImage) ?>">
    <meta property="og:image:width" content="1280">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Two Pixel operations dashboard: custom software for growing businesses">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($pageTitle) ?>">
    <meta name="twitter:description" content="<?= e($pageDescription) ?>">
    <meta name="twitter:image" content="<?= e($ogImage) ?>">

    <link rel="icon" href="/assets/images/favicon.svg" type="image/svg+xml">

    <!-- Inter, the typeface used in the Figma design -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">

    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
    <script src="<?= e(asset('js/main.js')) ?>" defer></script>

    <?php foreach (($page['schema'] ?? []) as $schema): ?>
    <script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_PRETTY_PRINT) ?></script>
    <?php endforeach; ?>
</head>
