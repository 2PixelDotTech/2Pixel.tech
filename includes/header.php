<?php
/**
 * Site header and main navigation.
 * Links are root-relative ("/#services") so they work from every page.
 */
$navItems = [
    'services'  => 'Services',
    'solutions' => 'Solutions',
    'work'      => 'Work',
    'process'   => 'Process',
    'about'     => 'About',
    'contact'   => 'Contact',
];
?>
<body>
<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header" data-header>
    <div class="container header-inner">
        <a class="brand" href="/" aria-label="Two Pixel home">
            <?= logo_mark(26) ?>
            <span>Two Pixel</span>
        </a>

        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
            <span class="nav-toggle-open"><?= icon('menu', 22) ?></span>
            <span class="nav-toggle-close"><?= icon('close', 22) ?></span>
            <span class="visually-hidden">Menu</span>
        </button>

        <nav class="site-nav" id="site-nav" aria-label="Main" data-nav>
            <ul>
                <?php foreach ($navItems as $id => $label): ?>
                <li><a href="/#<?= e($id) ?>" data-nav-link="<?= e($id) ?>"><?= e($label) ?></a></li>
                <?php endforeach; ?>
            </ul>
            <a class="btn btn-primary btn-sm nav-cta" href="/#contact" data-intent="call">
                Book a Call <?= icon('arrow', 14) ?>
            </a>
        </nav>
    </div>
</header>
