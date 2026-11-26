<?php
/**
 * Case study page: target of the "View project detail" button.
 * Uses only the content in the Figma design. It is clearly labelled as a concept project.
 */
require_once __DIR__ . '/includes/bootstrap.php';

$page = [
    'title' => 'Operations Management Platform: Concept Case Study | Two Pixel',
    'description' => 'How a centralised operations web app can replace spreadsheets, WhatsApp and manual reporting for a service business. A Two Pixel concept project.',
    'path' => '/operations-management-platform',
    'schema' => [
        [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => absolute_url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Work', 'item' => absolute_url('/#work')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => 'Operations Management Platform', 'item' => absolute_url('/operations-management-platform')],
            ],
        ],
        [
            '@context' => 'https://schema.org',
            '@type' => 'CreativeWork',
            'name' => 'Operations Management Platform (concept project)',
            'about' => 'Centralised web application for staff activity, task tracking, operational reporting and management visibility.',
            'creator' => ['@id' => absolute_url('/#organization')],
            'url' => absolute_url('/operations-management-platform'),
        ],
    ],
];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="main">
    <section class="section section-blue page-hero">
        <div class="container">
            <nav class="breadcrumbs" aria-label="Breadcrumb">
                <ol>
                    <li><a href="/">Home</a></li>
                    <li><a href="/#work">Work</a></li>
                    <li aria-current="page">Operations Management Platform</li>
                </ol>
            </nav>
            <p class="eyebrow eyebrow-light">Concept project · Service business</p>
            <h1 class="h1">Operations Management Platform</h1>
            <p class="lead lead-light">A centralised web application for staff activity, task tracking, operational reporting and management visibility.</p>
        </div>
    </section>

    <section class="section section-white">
        <div class="container case-detail">
            <div class="case-detail-grid">
                <article>
                    <p class="eyebrow">The problem</p>
                    <h2 class="h3">Daily operations ran on spreadsheets and chat.</h2>
                    <p>The team relied on spreadsheets, WhatsApp and manual reporting to manage daily operations.</p>
                </article>
                <article>
                    <p class="eyebrow">The solution</p>
                    <h2 class="h3">One place to run the work.</h2>
                    <p>A centralised web application for staff activity, task tracking, operational reporting and management visibility.</p>
                </article>
            </div>

            <div class="case-detail-visual">
                <?php $mockVariant = 'detail'; require __DIR__ . '/includes/dashboard-mockup.php'; ?>
            </div>

            <h2 class="h3">What the platform is designed to deliver</h2>
            <ul class="tick-list outcome-list">
                <li><?= icon('check', 13) ?> Centralised operations</li>
                <li><?= icon('check', 13) ?> Faster reporting</li>
                <li><?= icon('check', 13) ?> Reduced admin work</li>
                <li><?= icon('check', 13) ?> Better visibility</li>
            </ul>
            <p class="note">This is a concept project that shows the kind of
                <a href="/#services">custom business system</a> Two Pixel builds. It is not a client engagement.</p>
        </div>
    </section>

    <section class="section section-dark cta">
        <div class="cta-grid-bg" aria-hidden="true"></div>
        <div class="container section-head-center">
            <p class="eyebrow eyebrow-teal">Let's find the bottleneck</p>
            <h2 class="h2">Want something like this for your business?</h2>
            <p class="lead">Tell us how your team works today and we'll show you what software could automate.</p>
            <div class="btn-row btn-row-center">
                <a class="btn btn-primary" href="/#contact" data-intent="call">Book a Discovery Call <?= icon('arrow', 15) ?></a>
                <a class="btn btn-ghost" href="/#services">See What We Build</a>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
