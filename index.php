<?php
/**
 * Home page: the full one-page design from Figma.
 * Sections: hero, operational gap, services, solutions (industries), process,
 * work (case study), about (why us, toolkit, team), CTA, contact, footer.
 */
require_once __DIR__ . '/includes/bootstrap.php';

/* ---------- Content (kept as data so it is easy to edit) ---------- */

$problems = [
    ['Manual Administration', 'Teams spend hours repeating work that could be automated.'],
    ['Disconnected Information', 'Important data lives across spreadsheets, chats and paper documents.'],
    ['Poor Visibility', 'Managers struggle to see what is happening across the business in real time.'],
    ['Systems That Don\'t Fit', 'Off-the-shelf software often forces businesses to change how they operate.'],
];

$services = [
    [
        'icon' => 'window', 'tone' => 'blue',
        'title' => 'Custom Business Systems',
        'text' => 'Internal software tailored around your existing business processes.',
        'items' => ['Operations dashboards', 'CRM-style systems', 'Booking systems', 'Staff management'],
    ],
    [
        'icon' => 'spark', 'tone' => 'teal',
        'title' => 'Workflow Automation',
        'text' => 'Reduce repetitive administration and keep work moving automatically.',
        'items' => ['Automated notifications', 'Approvals', 'Data processing & reports', 'System integrations'],
    ],
    [
        'icon' => 'phone', 'tone' => 'blue',
        'title' => 'Web & Mobile Applications',
        'text' => 'Purpose-built tools for your employees, customers or partners.',
        'items' => ['Staff portals', 'Customer portals', 'Internal mobile apps', 'Business web applications'],
    ],
    [
        'icon' => 'stack', 'tone' => 'teal',
        'title' => 'System Modernisation',
        'text' => 'Replace outdated or fragmented systems with one reliable foundation.',
        'items' => ['Spreadsheet replacement', 'Legacy workflow redesign', 'Database systems', 'Process digitisation'],
    ],
];

$industries = [
    ['Medical Practices', 'Patient administration, bookings and internal workflows.'],
    ['Property Management', 'Tenant management, maintenance tracking and operational dashboards.'],
    ['Logistics', 'Fleet, delivery and job tracking.'],
    ['Retail & Inventory', 'Stock management, internal ordering and reporting.'],
    ['Professional Services', 'Client portals, project workflows and document management.'],
    ['Growing SMEs', 'Custom systems that replace spreadsheets and manual processes.'],
];

$steps = [
    ['Discover', 'We understand your workflows, bottlenecks and business requirements.'],
    ['Design', 'We map the solution and prototype the system before development.'],
    ['Build', 'We develop the system using reliable, maintainable architecture.'],
    ['Improve', 'We deploy, monitor and improve the system as your business grows.'],
];

$reasons = [
    ['Work Directly With The Developers', 'No layers of account managers between you and the people building the system.'],
    ['Built Around Your Workflow', 'We don\'t force your business into a generic template.'],
    ['Designed To Grow', 'We build software with maintainability and future expansion in mind.'],
];

$toolkit = ['JavaScript', 'TypeScript', 'React', '.NET', 'C#', 'Java', 'Kotlin', 'PHP', 'MySQL', 'PostgreSQL', 'REST APIs', 'GitHub', 'Cloud deployment'];

$team = [
    [
        'initials' => 'WN', 'tone' => 'blue', 'name' => 'Wanga Nembahe',
        'role' => 'Co-Founder & Software Developer',
        'focus' => ['Software development', 'System design', 'Web & mobile applications', 'Databases', 'API architecture'],
    ],
    [
        'initials' => 'LH', 'tone' => 'teal', 'name' => 'Lebokane Hlase',
        'role' => 'Co-Founder & Software Developer',
        'focus' => ['Software engineering', 'Business systems', 'Application development', 'Workflow automation'],
    ],
];

$contactMethods = ['email' => 'Email', 'phone' => 'Phone call', 'whatsapp' => 'WhatsApp', 'video' => 'Video call'];

/* ---------- Form state (the page is static, so the form always starts empty) ---------- */
$formErrors = [];
$oldVal = static fn(string $k, string $d = ''): string => $d;

/* ---------- SEO ---------- */
$orgId = absolute_url('/#organization');
$page = [
    'title' => 'Custom Business Software & Workflow Automation | Two Pixel',
    'description' => 'Two Pixel builds custom business systems, workflow automation and web & mobile apps that help growing businesses replace spreadsheets and manual admin.',
    'path' => '/',
    'schema' => [
        [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => $orgId,
            'name' => 'Two Pixel',
            'url' => absolute_url('/'),
            'logo' => absolute_url('assets/images/favicon.svg'),
            'email' => CONTACT_EMAIL,
            'description' => 'Custom software for growing businesses worldwide: business systems, workflow automation, web and mobile applications and system modernisation.',
            'areaServed' => 'Worldwide',
            'foundingLocation' => ['@type' => 'Place', 'name' => 'South Africa'],
            'founder' => array_map(static fn($m) => ['@type' => 'Person', 'name' => $m['name'], 'jobTitle' => $m['role']], $team),
            'knowsAbout' => ['Custom software development', 'Workflow automation', 'Business process digitisation', 'Web application development', 'Mobile application development'],
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => 'Software development services',
                'itemListElement' => array_map(static fn($s) => [
                    '@type' => 'Offer',
                    'itemOffered' => [
                        '@type' => 'Service',
                        'name' => $s['title'],
                        'description' => $s['text'],
                        'provider' => ['@id' => $orgId],
                        'areaServed' => 'Worldwide',
                    ],
                ], $services),
            ],
        ],
        [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => absolute_url('/#website'),
            'url' => absolute_url('/'),
            'name' => 'Two Pixel',
            'publisher' => ['@id' => $orgId],
            'inLanguage' => 'en',
        ],
    ],
];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="main">

    <!-- ============ Hero ============ -->
    <section class="hero" id="top">
        <div class="hero-grid-bg" aria-hidden="true"></div>
        <div class="container hero-inner">
            <div class="hero-copy">
                <p class="eyebrow eyebrow-dot">Custom software for growing businesses</p>
                <h1 class="hero-title">Replace manual processes with software built for <span class="text-blue">your business</span>.</h1>
                <p class="hero-lead">Two Pixel helps SMEs replace spreadsheets, paper logs and repetitive administration with reliable internal systems, automation and custom applications.</p>
                <div class="btn-row">
                    <a class="btn btn-primary" href="#contact" data-intent="call">Book a Discovery Call <?= icon('arrow', 15) ?></a>
                    <a class="btn btn-light" href="#services">See What We Build</a>
                </div>
                <ul class="hero-ticks">
                    <li><?= icon('check', 13) ?> Direct developer access</li>
                    <li><?= icon('check', 13) ?> Built for your workflow</li>
                </ul>
            </div>
            <div class="hero-visual">
                <?php $mockVariant = 'hero'; require __DIR__ . '/includes/dashboard-mockup.php'; ?>
            </div>
        </div>
    </section>

    <!-- ============ The operational gap ============ -->
    <section class="section section-white" aria-labelledby="gap-title">
        <div class="container">
            <div class="section-head reveal">
                <p class="eyebrow">The operational gap</p>
                <h2 id="gap-title" class="h2">Your business shouldn't depend on spreadsheets and WhatsApp forever.</h2>
                <p class="lead">What worked when the team was small becomes a bottleneck as the business grows.</p>
            </div>
            <div class="problem-grid">
                <?php foreach ($problems as $i => [$title, $text]): ?>
                <article class="problem reveal">
                    <span class="num"><?= sprintf('%02d', $i + 1) ?></span>
                    <div>
                        <h3 class="h4"><?= e($title) ?></h3>
                        <p><?= e($text) ?></p>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <p class="gap-closer reveal"><strong>That's where Two Pixel comes in.</strong></p>
        </div>
    </section>

    <!-- ============ Services ============ -->
    <section class="section section-muted" id="services" aria-labelledby="services-title">
        <div class="container">
            <div class="section-head section-head-split reveal">
                <div>
                    <p class="eyebrow">Our capabilities</p>
                    <h2 id="services-title" class="h2">What we build</h2>
                    <p class="lead">Practical software that removes friction from everyday operations.</p>
                </div>
                <p class="section-aside">From a focused internal tool to a complete operations platform, we build only what your business needs.</p>
            </div>
            <div class="service-grid">
                <?php foreach ($services as $i => $s): ?>
                <article class="service-card reveal">
                    <div class="service-top">
                        <span class="icon-tile tone-<?= e($s['tone']) ?>"><?= icon($s['icon'], 18) ?></span>
                        <span class="card-num"><?= sprintf('%02d', $i + 1) ?></span>
                    </div>
                    <h3 class="h3"><?= e($s['title']) ?></h3>
                    <p class="service-text"><?= e($s['text']) ?></p>
                    <ul class="tick-list">
                        <?php foreach ($s['items'] as $item): ?>
                        <li><?= icon('check', 12) ?> <?= e($item) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <a class="text-link" href="#contact" data-interest="<?= e($s['title']) ?>">
                        Discuss this service<span class="visually-hidden">: <?= e($s['title']) ?></span> <?= icon('arrow', 14) ?>
                    </a>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ============ Solutions / industries ============ -->
    <section class="section section-dark" id="solutions" aria-labelledby="solutions-title">
        <div class="container">
            <div class="section-head section-head-center reveal">
                <p class="eyebrow eyebrow-teal">Built around real operations</p>
                <h2 id="solutions-title" class="h2">Software for the work happening behind the scenes.</h2>
                <p class="lead">Every business operates differently. We design around the details that make yours work.</p>
            </div>
            <ul class="industry-grid reveal">
                <?php foreach ($industries as $i => [$title, $text]): ?>
                <li>
                    <a class="industry" href="#contact" data-interest="Software for <?= e($title) ?>">
                        <span class="card-num"><?= sprintf('%02d', $i + 1) ?></span>
                        <h3 class="h4"><?= e($title) ?></h3>
                        <p><?= e($text) ?></p>
                        <span class="circle-arrow"><?= icon('arrow', 14) ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

    <!-- ============ Process ============ -->
    <section class="section section-white" id="process" aria-labelledby="process-title">
        <div class="container">
            <div class="section-head reveal">
                <p class="eyebrow">A clear way forward</p>
                <h2 id="process-title" class="h2">From bottleneck to working system.</h2>
                <p class="lead">A practical process that keeps you involved without making software development your full-time job.</p>
            </div>
            <ol class="steps">
                <?php foreach ($steps as $i => [$title, $text]): ?>
                <li class="step reveal">
                    <span class="step-num" data-n="<?= sprintf('%02d', $i + 1) ?>" aria-hidden="true"></span>
                    <h3 class="h4"><?= e($title) ?></h3>
                    <p><?= e($text) ?></p>
                </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </section>

    <!-- ============ Work / case study ============ -->
    <section class="section section-blue" id="work" aria-labelledby="work-title">
        <div class="container case">
            <div class="case-copy reveal">
                <p class="eyebrow eyebrow-light">Concept project · Service business</p>
                <h2 id="work-title" class="h2">Operations Management Platform</h2>
                <div class="case-cols">
                    <div>
                        <h3 class="mini-label">The problem</h3>
                        <p>The team relied on spreadsheets, WhatsApp and manual reporting to manage daily operations.</p>
                    </div>
                    <div>
                        <h3 class="mini-label">The solution</h3>
                        <p>A centralised web application for staff activity, task tracking, operational reporting and management visibility.</p>
                    </div>
                </div>
                <ul class="tick-list tick-list-light">
                    <li><?= icon('check', 12) ?> Centralised operations</li>
                    <li><?= icon('check', 12) ?> Faster reporting</li>
                    <li><?= icon('check', 12) ?> Reduced admin work</li>
                    <li><?= icon('check', 12) ?> Better visibility</li>
                </ul>
                <a class="btn btn-white" href="/operations-management-platform">View project detail <?= icon('arrow', 15) ?></a>
            </div>
            <div class="case-visual reveal">
                <?php $mockVariant = 'case'; require __DIR__ . '/includes/dashboard-mockup.php'; ?>
            </div>
        </div>
    </section>

    <!-- ============ About: why Two Pixel ============ -->
    <section class="section section-muted" id="about" aria-labelledby="about-title">
        <div class="container">
            <div class="section-head reveal">
                <p class="eyebrow">Why Two Pixel</p>
                <h2 id="about-title" class="h2">Small team. Direct communication. Serious engineering.</h2>
                <p class="lead">You work with the people making the technical decisions and writing the code.</p>
            </div>
            <div class="reason-grid">
                <?php foreach ($reasons as $i => [$title, $text]): ?>
                <article class="reason reveal">
                    <span class="num"><?= sprintf('%02d', $i + 1) ?></span>
                    <h3 class="h4"><?= e($title) ?></h3>
                    <p><?= e($text) ?></p>
                </article>
                <?php endforeach; ?>
            </div>
            <p class="motto reveal">Enterprise-minded engineering <i class="dot dot-teal" aria-hidden="true"></i> without enterprise agency overhead.</p>
        </div>
    </section>

    <!-- ============ Toolkit ============ -->
    <section class="section section-white section-tight" aria-labelledby="toolkit-title">
        <div class="container toolkit">
            <div class="reveal">
                <p class="eyebrow">Our toolkit</p>
                <h2 id="toolkit-title" class="h3 toolkit-title">Technology chosen for the problem, not the trend.</h2>
            </div>
            <ul class="chips reveal" aria-label="Technologies we use">
                <?php foreach ($toolkit as $tool): ?>
                <li class="chip"><?= e($tool) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

    <!-- ============ Team ============ -->
    <section class="section section-muted" id="team" aria-labelledby="team-title">
        <div class="container">
            <div class="section-head section-head-center reveal">
                <p class="eyebrow">The team</p>
                <h2 id="team-title" class="h2">Built by the people who build the software.</h2>
                <p class="lead">A focused partnership combining hands-on software engineering with an understanding of how businesses operate.</p>
            </div>
            <div class="team-grid">
                <?php foreach ($team as $m): ?>
                <article class="person reveal">
                    <div class="person-head">
                        <span class="avatar tone-<?= e($m['tone']) ?>" aria-hidden="true"><?= e($m['initials']) ?></span>
                        <div>
                            <h3 class="h4"><?= e($m['name']) ?></h3>
                            <p class="person-role"><?= e($m['role']) ?></p>
                        </div>
                    </div>
                    <p class="mini-label">Focus areas</p>
                    <ul class="chips chips-sm">
                        <?php foreach ($m['focus'] as $f): ?>
                        <li class="chip"><?= e($f) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ============ CTA ============ -->
    <section class="section section-dark cta" aria-labelledby="cta-title">
        <div class="cta-grid-bg" aria-hidden="true"></div>
        <div class="container section-head-center reveal">
            <p class="eyebrow eyebrow-teal">Let's find the bottleneck</p>
            <h2 id="cta-title" class="h2">What's slowing your business down?</h2>
            <p class="lead">If your team is spending too much time on spreadsheets, manual administration or disconnected systems, let's look at what software could automate.</p>
            <div class="btn-row btn-row-center">
                <a class="btn btn-primary" href="#contact" data-intent="call">Book a Discovery Call <?= icon('arrow', 15) ?></a>
                <a class="btn btn-ghost" href="#contact" data-intent="process">Tell Us About Your Process</a>
            </div>
        </div>
    </section>

    <!-- ============ Contact ============ -->
    <section class="section section-white" id="contact" aria-labelledby="contact-title">
        <div class="container contact">
            <div class="contact-copy reveal">
                <p class="eyebrow">Start a conversation</p>
                <h2 id="contact-title" class="h2">Tell us what isn't working.</h2>
                <p class="lead">You don't need to know exactly what software you need. Tell us about the process, bottleneck or repetitive task you're trying to fix.</p>
                <ul class="tick-list tick-list-col">
                    <li><?= icon('check', 13) ?> No technical specification required</li>
                    <li><?= icon('check', 13) ?> Direct response from a developer</li>
                    <li><?= icon('check', 13) ?> No-obligation first conversation</li>
                </ul>
                <a class="text-link email-link" href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?> <?= icon('arrow', 14) ?></a>
            </div>

            <form class="contact-form reveal" action="/api/contact" method="post" novalidate data-contact-form>
                <div class="form-status" role="status" aria-live="polite" tabindex="-1" data-form-status hidden></div>
                <noscript><p class="form-status is-error">This form needs JavaScript. You can email us instead at <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a>.</p></noscript>

                <input type="hidden" name="form_started" value="" data-form-started>
                <input type="hidden" name="interest" value="<?= e($oldVal('interest')) ?>" data-interest-input>
                <!-- Spam trap: hidden from people, often filled in by bots -->
                <div class="hp" aria-hidden="true">
                    <label for="website">Leave this field empty</label>
                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                </div>

                <p class="interest-note" data-interest-note<?= $oldVal('interest') ? '' : ' hidden' ?>>
                    Enquiring about: <strong data-interest-label><?= e($oldVal('interest')) ?></strong>
                    <button type="button" class="interest-clear" data-interest-clear aria-label="Clear enquiry topic"><?= icon('close', 12) ?></button>
                </p>

                <div class="form-grid">
                    <?php
                    $fields = [
                        ['full_name', 'Full Name', 'text', 'Your full name', 'name', true],
                        ['company', 'Company Name', 'text', 'Your company', 'organization', false],
                        ['email', 'Email', 'email', 'you@company.com', 'email', true],
                        ['phone', 'Phone', 'tel', 'Include your country code', 'tel', false],
                    ];
                    foreach ($fields as [$name, $label, $type, $ph, $ac, $req]):
                        $err = $formErrors[$name] ?? '';
                    ?>
                    <div class="field">
                        <label for="f-<?= e($name) ?>"><?= e($label) ?><?= $req ? ' <span class="req" aria-hidden="true">*</span>' : '' ?></label>
                        <input id="f-<?= e($name) ?>" name="<?= e($name) ?>" type="<?= e($type) ?>" placeholder="<?= e($ph) ?>" autocomplete="<?= e($ac) ?>"
                            value="<?= e($oldVal($name)) ?>" maxlength="<?= $name === 'email' ? 254 : 120 ?>"<?= $req ? ' required' : '' ?>
                            aria-describedby="e-<?= e($name) ?>"<?= $err ? ' aria-invalid="true"' : '' ?>>
                        <p class="field-error" id="e-<?= e($name) ?>"><?= e($err) ?></p>
                    </div>
                    <?php endforeach; ?>

                    <div class="field field-full">
                        <label for="f-business">What does your business do?</label>
                        <input id="f-business" name="business" type="text" placeholder="A short description of your business" maxlength="200" value="<?= e($oldVal('business')) ?>" aria-describedby="e-business">
                        <p class="field-error" id="e-business"><?= e($formErrors['business'] ?? '') ?></p>
                    </div>

                    <div class="field field-full">
                        <label for="f-process">What process would you like to improve? <span class="req" aria-hidden="true">*</span></label>
                        <textarea id="f-process" name="process" rows="4" placeholder="Tell us where work gets stuck, repeated or lost…" maxlength="3000" required aria-describedby="e-process"<?= !empty($formErrors['process']) ? ' aria-invalid="true"' : '' ?>><?= e($oldVal('process')) ?></textarea>
                        <p class="field-error" id="e-process"><?= e($formErrors['process'] ?? '') ?></p>
                    </div>

                    <div class="field">
                        <label for="f-budget">Approximate project budget</label>
                        <input id="f-budget" name="budget" type="text" maxlength="100" placeholder="Any amount, any currency" value="<?= e($oldVal('budget')) ?>">
                        <p class="field-error" id="e-budget"></p>
                    </div>
                    <div class="field">
                        <label for="f-method">Preferred contact method</label>
                        <div class="select-wrap">
                            <select id="f-method" name="contact_method" data-method-select>
                                <?php foreach ($contactMethods as $val => $label): ?>
                                <option value="<?= e($val) ?>"<?= $oldVal('contact_method', 'email') === $val ? ' selected' : '' ?>><?= e($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?= icon('chevron', 14) ?>
                        </div>
                    </div>
                </div>

                <!-- Cloudflare Turnstile: a privacy-friendly check that the sender is a person, not a bot -->
                <div class="turnstile" data-turnstile data-sitekey="<?= e(TURNSTILE_SITE_KEY) ?>"></div>

                <button class="btn btn-primary btn-block" type="submit" data-submit>
                    <span data-submit-label>Start the Conversation</span> <?= icon('arrow', 15) ?>
                </button>
                <p class="form-privacy">We only use these details to reply to your enquiry. See our <a href="/privacy-policy">Privacy Policy</a>.</p>
            </form>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
