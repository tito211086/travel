<?php
require __DIR__ . '/includes/bootstrap.php';

$featuredDestinations = get_destinations(6, true);
$featuredPackages     = get_packages(['featured' => true], 3);
$testimonials         = get_testimonials(3);
$regions              = get_regions();

require __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <div class="container hero-grid">
        <div class="hero-copy">
            <p class="eyebrow">Small-group &amp; tailor-made travel</p>
            <h1 class="display-xl">Go further.<br><span class="accent">Travel</span> better.</h1>
            <p class="lede">Hand-built journeys with local guides, fewer crowds and more time for the moments that stay with you.</p>
            <form class="search-bar" action="<?= e(url('packages.php')) ?>" method="get" role="search">
                <label class="field">
                    <span class="field-label">Where to?</span>
                    <input type="search" name="q" placeholder="Country, city or journey" maxlength="80">
                </label>
                <label class="field">
                    <span class="field-label">Trip length</span>
                    <select name="max_days">
                        <option value="">Any length</option>
                        <option value="7">Up to 7 days</option>
                        <option value="10">Up to 10 days</option>
                        <option value="14">Up to 14 days</option>
                    </select>
                </label>
                <button class="btn btn-primary" type="submit">Search journeys</button>
            </form>
        </div>
        <div class="hero-art" aria-hidden="true">
            <div class="shape shape-sun"></div>
            <div class="shape shape-block"></div>
            <div class="shape shape-arc"></div>
            <div class="hero-stat"><strong>48</strong><span>countries</span></div>
        </div>
    </div>
    <div class="container">
        <ul class="stat-row">
            <li><strong>12k+</strong><span>Happy travellers</span></li>
            <li><strong>4.9/5</strong><span>Average rating</span></li>
            <li><strong>12</strong><span>Max group size</span></li>
            <li><strong>24/7</strong><span>On-trip support</span></li>
        </ul>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <p class="eyebrow">01 — Destinations</p>
                <h2 class="display-md">Where will you go next?</h2>
            </div>
            <a class="link-arrow" href="<?= e(url('destinations.php')) ?>">All destinations</a>
        </div>
        <div class="dest-grid">
            <?php foreach ($featuredDestinations as $d) { include __DIR__ . '/includes/destination-card.php'; } ?>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <div>
                <p class="eyebrow">02 — Featured journeys</p>
                <h2 class="display-md">Loved by our travellers</h2>
            </div>
            <a class="link-arrow" href="<?= e(url('packages.php')) ?>">Browse all journeys</a>
        </div>
        <div class="card-grid">
            <?php foreach ($featuredPackages as $p) { include __DIR__ . '/includes/package-card.php'; } ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <p class="eyebrow">03 — Why Hello Travel</p>
        <h2 class="display-md">Designed around you.</h2>
        <div class="feature-grid">
            <div class="feature"><span class="feature-num mono">A</span><h3>Local experts</h3><p>Every trip is led by guides who live where you're going, so you see the place as locals do.</p></div>
            <div class="feature"><span class="feature-num mono">B</span><h3>Small groups</h3><p>Never more than twelve travellers. More space, more flexibility, more real conversations.</p></div>
            <div class="feature"><span class="feature-num mono">C</span><h3>Flexible booking</h3><p>Change dates up to 30 days before departure at no cost. Plans change — we get it.</p></div>
            <div class="feature"><span class="feature-num mono">D</span><h3>Responsible by default</h3><p>Locally owned stays, fair wages and carbon-balanced itineraries on every departure.</p></div>
        </div>
    </div>
</section>

<?php if ($testimonials): ?>
<section class="section section-dark">
    <div class="container">
        <p class="eyebrow">04 — Travellers say</p>
        <div class="quote-grid">
            <?php foreach ($testimonials as $t): ?>
                <figure class="quote">
                    <?= stars((float) $t['rating']) ?>
                    <blockquote>“<?= e($t['quote']) ?>”</blockquote>
                    <figcaption><strong><?= e($t['author_name']) ?></strong><span><?= e($t['trip_name']) ?></span></figcaption>
                </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section">
    <div class="container">
        <div class="cta-block">
            <div>
                <p class="eyebrow">Tailor-made</p>
                <h2 class="display-md">Not seeing the perfect trip?</h2>
                <p class="lede">Tell us where you're dreaming of and a travel designer will build it with you — free, with no obligation.</p>
            </div>
            <a class="btn btn-light btn-lg" href="<?= e(url('contact.php')) ?>">Start planning</a>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
