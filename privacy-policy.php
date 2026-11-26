<?php
/**
 * Privacy Policy, written for South Africa's Protection of Personal Information Act 4 of 2013 (POPIA).
 * It only describes what this website actually does. If you add analytics, a newsletter,
 * chat widgets or new forms, update this page first.
 *
 * Not legal advice: have it reviewed before launch.
 */
require_once __DIR__ . '/includes/bootstrap.php';

$effectiveDate = '24 November 2026';
$infoOfficer = (string) config('INFORMATION_OFFICER', 'Wanga Nembahe');
$address = (string) config('BUSINESS_ADDRESS', '');

$page = [
    'title' => 'Privacy Policy | Two Pixel',
    'description' => 'How Two Pixel collects, uses and protects your personal information in line with South Africa\'s Protection of Personal Information Act (POPIA).',
    'path' => '/privacy-policy',
    'schema' => [
        [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => absolute_url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Privacy Policy', 'item' => absolute_url('/privacy-policy')],
            ],
        ],
    ],
];

$sections = [
    'who-we-are' => 'Who we are',
    'what-we-collect' => 'What we collect',
    'why' => 'Why we use it',
    'sharing' => 'Who we share it with',
    'transfers' => 'Transfers outside South Africa',
    'retention' => 'How long we keep it',
    'security' => 'How we protect it',
    'cookies' => 'Cookies',
    'your-rights' => 'Your rights',
    'complaints' => 'Complaints',
    'changes' => 'Changes to this policy',
];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="main">
    <section class="section section-muted page-hero-light">
        <div class="container">
            <nav class="breadcrumbs breadcrumbs-dark" aria-label="Breadcrumb">
                <ol>
                    <li><a href="/">Home</a></li>
                    <li aria-current="page">Privacy Policy</li>
                </ol>
            </nav>
            <p class="eyebrow">Legal</p>
            <h1 class="h1">Privacy Policy</h1>
            <p class="lead">How Two Pixel collects, uses and protects your personal information under the Protection of Personal Information Act 4 of 2013 (POPIA).</p>
            <p class="policy-date">Effective date: <?= e($effectiveDate) ?></p>
        </div>
    </section>

    <section class="section section-white">
        <div class="container policy">
            <nav class="policy-toc" aria-label="On this page">
                <p class="mini-label">On this page</p>
                <ol>
                    <?php foreach ($sections as $id => $label): ?>
                    <li><a href="#<?= e($id) ?>"><?= e($label) ?></a></li>
                    <?php endforeach; ?>
                </ol>
            </nav>

            <div class="policy-body">
                <p>This policy applies to everyone who uses this website or contacts us, wherever you live. We are based in South Africa, so POPIA governs how we process personal information. If the law where you live gives you extra rights, contact us and we will deal with your request in line with that law too.</p>

                <h2 id="who-we-are">1. Who we are</h2>
                <p><strong>Two Pixel</strong> builds custom business software. For POPIA purposes we are the <em>responsible party</em>: we decide why and how your personal information is processed.</p>
                <ul>
                    <li><strong>Information Officer:</strong> <?= e($infoOfficer) ?></li>
                    <li><strong>Email:</strong> <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a></li>
                    <li><strong>Address:</strong> <?= $address !== '' ? e($address) : 'Available on request by email' ?></li>
                </ul>

                <h2 id="what-we-collect">2. What we collect</h2>
                <p><strong>Information you give us</strong> when you use our contact form or email us:</p>
                <ul>
                    <li>your name, email address and, if you choose, your phone number and company name;</li>
                    <li>what your business does, the process you want to improve, an approximate budget and how you prefer to be contacted.</li>
                </ul>
                <p>Please don't send us special personal information (such as health, religious or biometric information) or information about children. We don't need it to answer your enquiry.</p>
                <p><strong>Information collected automatically:</strong></p>
                <ul>
                    <li><strong>A spam check when you use the contact form.</strong> We use Cloudflare Turnstile to confirm that a person, not a bot, is sending the form. It loads only when you scroll to the form, and Cloudflare processes your IP address and technical details about your browser and device to run the check.</li>
                    <li><strong>Standard request logs</strong> kept by our hosting provider, Cloudflare (IP address, browser type, pages requested and time), used to deliver and secure the website.</li>
                </ul>
                <p>We don't use advertising trackers, sell personal information or build marketing profiles.</p>

                <h2 id="why">3. Why we use it</h2>
                <p>POPIA only allows processing for a specific, lawful purpose. We use your information to:</p>
                <ul>
                    <li><strong>reply to your enquiry and discuss a possible project.</strong> This is a step you asked us to take before any agreement is made (POPIA section 11(1)(b));</li>
                    <li><strong>keep the website and form secure</strong> and block spam and abuse, including the Turnstile check. This is our legitimate interest (section 11(1)(f));</li>
                    <li><strong>meet legal obligations</strong>, for example tax and accounting records if you become a client (section 11(1)(c)).</li>
                </ul>
                <p><strong>Direct marketing:</strong> we will not send you newsletters or marketing unless you have opted in, as required by POPIA section 69. Every marketing message will include a way to unsubscribe.</p>
                <p>Giving us your information is voluntary. Without a name, an email address and a short description of your request, though, we can't reply to you.</p>

                <h2 id="sharing">4. Who we share it with</h2>
                <p>We only share personal information with service providers (<em>operators</em>) who help us run our business. They may only use it on our instructions and must keep it secure:</p>
                <ul>
                    <li><strong>Cloudflare, Inc.</strong> hosts the website, runs the contact form, stores a backup copy of enquiries and provides the Turnstile spam check. See <a href="https://www.cloudflare.com/privacypolicy/" rel="noopener">Cloudflare's Privacy Policy</a> and the <a href="https://www.cloudflare.com/turnstile-privacy-policy/" rel="noopener">Turnstile privacy addendum</a>.</li>
                    <li><strong>Resend</strong> delivers the email that tells us about your enquiry. See <a href="https://resend.com/legal/privacy-policy" rel="noopener">Resend's Privacy Policy</a>.</li>
                    <li><strong>Our email provider</strong> receives, stores and sends the messages we exchange with you.</li>
                    <li><strong>Google Fonts:</strong> your browser downloads our typeface from Google, which receives your IP address and browser details. See <a href="https://policies.google.com/privacy" rel="noopener">Google's Privacy Policy</a>.</li>
                </ul>
                <p>We may also disclose information when the law requires it, or to protect our rights or someone's safety. We never sell or rent personal information.</p>

                <h2 id="transfers">5. Transfers outside South Africa</h2>
                <p>Some of our service providers store or process information on servers outside South Africa, including in the United States and on Cloudflare's worldwide network. Under POPIA section 72 we only transfer personal information when the recipient is subject to laws, binding rules or an agreement that protect it at least as well as POPIA does, or when another ground in section 72 applies (for example, the transfer is needed to respond to your request).</p>

                <h2 id="retention">6. How long we keep it</h2>
                <ul>
                    <li><strong>Enquiries:</strong> up to 12 months after our last contact with you, unless you become a client.</li>
                    <li><strong>Client records:</strong> as long as the law requires (for example, financial records under South African tax law), then deleted or de-identified.</li>
                    <li><strong>The backup copy of your enquiry</strong> stored with Cloudflare: deleted automatically after 12 months.</li>
                    <li><strong>Spam-check and request logs:</strong> kept by Cloudflare for the periods set out in its own policies.</li>
                </ul>
                <p>You can ask us to delete your enquiry sooner (see <a href="#your-rights">Your rights</a>).</p>

                <h2 id="security">7. How we protect it</h2>
                <p>As POPIA section 19 requires, we take reasonable technical and organisational measures to protect personal information. These include encrypted connections (HTTPS), automated bot and spam checks, keeping enquiries out of the public website, keeping access keys secret, and limiting access to the people who need it. If we believe your personal information has been accessed without authorisation, we will notify you and the Information Regulator as section 22 requires.</p>

                <h2 id="cookies">8. Cookies</h2>
                <p><strong>Our website doesn't set any cookies.</strong> When you reach the contact form, Cloudflare Turnstile runs its spam check from Cloudflare's own domain. It is used only to tell people from bots, not for advertising or tracking (see the <a href="https://www.cloudflare.com/turnstile-privacy-policy/" rel="noopener">Turnstile privacy addendum</a>). We don't use analytics or advertising cookies. If we ever add them, we will ask for your consent first.</p>

                <h2 id="your-rights">9. Your rights</h2>
                <p>Under POPIA you have the right to:</p>
                <ul>
                    <li><strong>know</strong> whether we hold personal information about you, and ask for a copy (section 23);</li>
                    <li><strong>ask us to correct or delete</strong> information that is inaccurate, out of date, excessive or unlawfully obtained (section 24);</li>
                    <li><strong>object</strong> to processing based on our legitimate interests, and to direct marketing at any time (section 11(3));</li>
                    <li><strong>withdraw consent</strong> where processing is based on consent;</li>
                    <li><strong>complain</strong> to the Information Regulator (see below).</li>
                </ul>
                <p>To use any of these rights, email <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a>. We may need to confirm your identity first. We will respond within a reasonable time, and access requests are handled as the Promotion of Access to Information Act (PAIA) sets out.</p>

                <h2 id="complaints">10. Complaints</h2>
                <p>If you're unhappy with how we've handled your information, please contact us first so we can try to put it right. You also have the right to complain to South Africa's <strong>Information Regulator</strong>: <a href="https://inforegulator.org.za" rel="noopener">inforegulator.org.za</a>. If you live outside South Africa, you may also contact the data protection authority in your country.</p>

                <h2 id="changes">11. Changes to this policy</h2>
                <p>We may update this policy when our services or the law change. The effective date at the top shows when it last changed. If a change significantly affects how we use your information, we will take reasonable steps to tell you.</p>

                <p class="policy-back"><a class="text-link" href="/#contact">Back to the contact form <?= icon('arrow', 14) ?></a></p>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
