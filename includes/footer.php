</main>
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a class="brand brand-invert" href="<?= e(url('index.php')) ?>">
                    <span class="brand-mark" aria-hidden="true"></span>
                    <span class="brand-name">Hello<span>Travel</span></span>
                </a>
                <p><?= e(SITE_TAGLINE) ?> Small groups, local guides and itineraries built around how you like to travel.</p>
            </div>
            <div>
                <h2 class="footer-title">Explore</h2>
                <ul class="footer-links">
                    <li><a href="<?= e(url('destinations.php')) ?>">Destinations</a></li>
                    <li><a href="<?= e(url('packages.php')) ?>">All journeys</a></li>
                    <li><a href="<?= e(url('packages.php', ['style' => 'Adventure'])) ?>">Adventure</a></li>
                    <li><a href="<?= e(url('packages.php', ['style' => 'Culture'])) ?>">Culture</a></li>
                </ul>
            </div>
            <div>
                <h2 class="footer-title">Company</h2>
                <ul class="footer-links">
                    <li><a href="<?= e(url('about.php')) ?>">About us</a></li>
                    <li><a href="<?= e(url('contact.php')) ?>">Contact</a></li>
                    <li><a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a></li>
                    <li><a href="tel:<?= e(preg_replace('/[^0-9+]/', '', SITE_PHONE)) ?>"><?= e(SITE_PHONE) ?></a></li>
                </ul>
            </div>
            <div>
                <h2 class="footer-title">Newsletter</h2>
                <p class="footer-small">One thoughtful email a month. New routes, early access, no noise.</p>
                <form class="inline-form" method="post" action="<?= e(url('subscribe.php')) ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="return" value="<?= e($_SERVER['REQUEST_URI'] ?? '/') ?>">
                    <label class="sr-only" for="footer-email">Email address</label>
                    <input id="footer-email" type="email" name="email" placeholder="you@example.com" required maxlength="190">
                    <div class="hp" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
                    <button class="btn btn-accent" type="submit">Join</button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; <?= date('Y') ?> <?= e(SITE_NAME) ?>. All rights reserved.</span>
            <span class="mono">Made for curious travellers.</span>
        </div>
    </div>
</footer>
<script src="<?= e(asset('js/main.js')) ?>" defer></script>
</body>
</html>
