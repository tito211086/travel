<?php
require __DIR__ . '/includes/bootstrap.php';

$regions = get_regions();
$region  = isset($_GET['region']) && in_array($_GET['region'], $regions, true) ? $_GET['region'] : '';
$destinations = get_destinations(null, false, $region);

$pageTitle = 'Destinations';
$metaDescription = 'Explore every Hello Travel destination across the world.';
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Destinations</p>
        <h1 class="display-lg">The world, curated.</h1>
        <p class="lede">From Arctic light to desert dunes — <?= count($destinations) ?> places we know inside out.</p>
        <nav class="chips" aria-label="Filter by region">
            <a class="chip<?= $region === '' ? ' is-active' : '' ?>" href="<?= e(url('destinations.php')) ?>">All</a>
            <?php foreach ($regions as $r): ?>
                <a class="chip<?= $region === $r ? ' is-active' : '' ?>" href="<?= e(url('destinations.php', ['region' => $r])) ?>"><?= e($r) ?></a>
            <?php endforeach; ?>
        </nav>
    </div>
</section>
<section class="section section-tight">
    <div class="container">
        <?php if ($destinations): ?>
            <div class="dest-grid">
                <?php foreach ($destinations as $d) { include __DIR__ . '/includes/destination-card.php'; } ?>
            </div>
        <?php else: ?>
            <p class="empty">No destinations found in this region yet.</p>
        <?php endif; ?>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
