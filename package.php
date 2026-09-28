<?php
require __DIR__ . '/includes/bootstrap.php';

$slug = isset($_GET['slug']) && is_string($_GET['slug']) ? $_GET['slug'] : '';
$st = db()->prepare('SELECT p.*, d.name AS destination_name, d.slug AS destination_slug, d.country
                     FROM packages p JOIN destinations d ON d.id = p.destination_id
                     WHERE p.slug = ? AND p.is_active = 1');
$st->execute([$slug]);
$package = $st->fetch();
if (!$package) {
    not_found();
}

$st = db()->prepare('SELECT * FROM package_days WHERE package_id = ? ORDER BY day_number');
$st->execute([$package['id']]);
$days = $st->fetchAll();

$highlights = array_filter(array_map('trim', explode("\n", (string) $package['highlights'])));
$included   = array_filter(array_map('trim', explode("\n", (string) $package['included'])));

$pageTitle = $package['title'];
$metaDescription = $package['summary'];
require __DIR__ . '/includes/header.php';
?>
<section class="detail-hero" style="--tile: <?= e($package['accent_color']) ?>">
    <?php if (!empty($package['image_url'])): ?><img src="<?= e($package['image_url']) ?>" alt=""><?php endif; ?>
    <div class="container">
        <nav class="crumbs mono" aria-label="Breadcrumb">
            <a href="<?= e(url('packages.php')) ?>">Journeys</a> /
            <a href="<?= e(url('destination.php', ['slug' => $package['destination_slug']])) ?>"><?= e($package['destination_name']) ?></a>
        </nav>
        <h1 class="display-lg"><?= e($package['title']) ?></h1>
        <ul class="pill-row">
            <li><?= (int) $package['duration_days'] ?> days</li>
            <li><?= e($package['style']) ?></li>
            <li>Max <?= (int) $package['group_size'] ?> travellers</li>
            <li><?= stars((float) $package['rating']) ?></li>
        </ul>
    </div>
</section>

<section class="section">
    <div class="container detail-grid">
        <div>
            <p class="lede"><?= e($package['summary']) ?></p>
            <div class="prose"><?= nl2br(e($package['description'])) ?></div>

            <?php if ($highlights): ?>
                <h2 class="h-section">Highlights</h2>
                <ul class="check-list">
                    <?php foreach ($highlights as $h): ?><li><?= e($h) ?></li><?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if ($days): ?>
                <h2 class="h-section">Day by day</h2>
                <ol class="timeline">
                    <?php foreach ($days as $day): ?>
                        <li>
                            <span class="timeline-day mono">Day <?= (int) $day['day_number'] ?></span>
                            <h3><?= e($day['title']) ?></h3>
                            <p><?= e($day['description']) ?></p>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>

            <?php if ($included): ?>
                <h2 class="h-section">What's included</h2>
                <ul class="check-list">
                    <?php foreach ($included as $i): ?><li><?= e($i) ?></li><?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <aside class="booking-box" id="enquire">
            <p class="price price-lg"><small>from</small> <?= e(money((float) $package['price'], $package['currency'])) ?><small> / person</small></p>
            <h2 class="footer-title">Request this journey</h2>
            <form method="post" action="<?= e(url('enquire.php')) ?>" class="stack-form" novalidate data-validate>
                <?= csrf_field() ?>
                <input type="hidden" name="package_id" value="<?= (int) $package['id'] ?>">
                <label class="field"><span class="field-label">Full name</span>
                    <input type="text" name="name" required maxlength="120" value="<?= e(old('name')) ?>"></label>
                <label class="field"><span class="field-label">Email</span>
                    <input type="email" name="email" required maxlength="190" value="<?= e(old('email')) ?>"></label>
                <label class="field"><span class="field-label">Phone <small>(optional)</small></span>
                    <input type="tel" name="phone" maxlength="40" value="<?= e(old('phone')) ?>"></label>
                <div class="field-row">
                    <label class="field"><span class="field-label">Departure</span>
                        <input type="date" name="travel_date" min="<?= date('Y-m-d', strtotime('+7 days')) ?>" value="<?= e(old('travel_date')) ?>"></label>
                    <label class="field"><span class="field-label">Travellers</span>
                        <input type="number" name="travellers" min="1" max="<?= (int) $package['group_size'] ?>" value="<?= e(old('travellers', '2')) ?>"></label>
                </div>
                <label class="field"><span class="field-label">Message <small>(optional)</small></span>
                    <textarea name="message" rows="3" maxlength="2000"><?= e(old('message')) ?></textarea></label>
                <div class="hp" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
                <button class="btn btn-primary btn-block" type="submit">Send enquiry</button>
                <p class="form-note">No payment now. A specialist replies within one business day.</p>
            </form>
            <?php clear_old(); ?>
        </aside>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
