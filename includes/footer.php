<?php
/** Site footer (shared by all pages). */
?>
<footer class="site-footer">
    <div class="container">
        <div class="footer-top">
            <div>
                <a class="brand brand-light" href="/" aria-label="Two Pixel home">
                    <?= logo_mark(26, true) ?>
                    <span>Two Pixel</span>
                </a>
                <p class="footer-tagline">Custom software for growing businesses, wherever they operate.</p>
            </div>
            <nav aria-label="Footer">
                <ul class="footer-nav">
                    <li><a href="/#services">Services</a></li>
                    <li><a href="/#solutions">Solutions</a></li>
                    <li><a href="/#work">Work</a></li>
                    <li><a href="/#process">Process</a></li>
                    <li><a href="/#about">About</a></li>
                </ul>
            </nav>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Two Pixel. Built in South Africa. <a href="/privacy-policy">Privacy Policy</a></p>
            <p>Software that fits the way you work.</p>
        </div>
    </div>
</footer>
</body>
</html>
