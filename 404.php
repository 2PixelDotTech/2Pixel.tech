<?php
/** Custom "page not found" page, built to dist/404.html. Cloudflare serves it with a 404 status; never indexed. */
require_once __DIR__ . '/includes/bootstrap.php';

$page = [
    'title' => 'Page not found | Two Pixel',
    'description' => 'The page you were looking for could not be found.',
    'path' => '/404',
    'noindex' => true,
];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="main">
    <section class="section section-muted not-found">
        <div class="container section-head-center">
            <p class="eyebrow">Error 404</p>
            <h1 class="h1">This page doesn't exist.</h1>
            <p class="lead">The link may be broken, or the page may have moved.</p>
            <div class="btn-row btn-row-center">
                <a class="btn btn-primary" href="/">Back to home <?= icon('arrow', 15) ?></a>
                <a class="btn btn-light" href="/#contact">Contact us</a>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
