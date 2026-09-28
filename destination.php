<?php
require __DIR__ . '/includes/bootstrap.php';

$slug = isset($_GET['slug']) && is_string($_GET['slug']) ? $_GET['slug'] : '';
$st = db()->prepare('SELECT * FROM destinations WHERE slug = ? AND is_active = 1');
$st->execute([$slug]);
$destination = $st->fetch();
if (!$destination) {
    not_found();
}
$packages = get_packages(['destination_id' => $destination['id']]);

$pageTitle = $destination['name'] . ', ' . $destination['country'];
$metaDescription = $destination['summary'];
require __DIR__ . '/includes/header.php';
?>
<section class="detail-hero" style="--tile: <?= e($destination['accent_color']) ?>">
    <?php if (!empty($destination['image_url'])): ?><img src="<?= e($destination['image_url']) ?>" alt=""><?php endif; ?>
    <div class="container">
        <nav class="crumbs mono" aria-label="Breadcrumb"><a href="<?= e(url('destinations.php')) ?>">Destinations</a> / <?= e($destination['region']) ?></nav>
        <h1 class="display-xl"><?= e($destination['name']) ?></h1>
        <p class="detail-sub"><?= e($destination['country']) ?></p>
    </div>
</section>
<section class="section">
    <div class="container detail-grid">
        <div>
            <p class="eyebrow">Overview</p>
            <p class="lede"><?= e($destination['summary']) ?></p>
            <div class="prose"><?= nl2br(e($destination['description'])) ?></div>
        </div>
        <aside class="fact-box">
            <h2 class="footer-title">Good to know</h2>
            <dl>
                <dt>Best time</dt><dd><?= e($destination['best_time']) ?></dd>
                <dt>Region</dt><dd><?= e($destination['region']) ?></dd>
                <dt>Journeys</dt><dd><?= count($packages) ?></dd>
            </dl>
            <a class="btn btn-primary btn-block" href="<?= e(url('contact.php', ['destination' => $destination['name']])) ?>">Ask a specialist</a>
        </aside>
    </div>
</section>
<section class="section section-alt">
    <div class="container">
        <p class="eyebrow">Journeys in <?= e($destination['name']) ?></p>
        <h2 class="display-md">Ways to explore</h2>
        <?php if ($packages): ?>
            <div class="card-grid">
                <?php foreach ($packages as $p) { include __DIR__ . '/includes/package-card.php'; } ?>
            </div>
        <?php else: ?>
            <p class="empty">No scheduled journeys yet — <a href="<?= e(url('contact.php', ['destination' => $destination['name']])) ?>">ask us to build one for you</a>.</p>
        <?php endif; ?>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
