<?php
require __DIR__ . '/includes/bootstrap.php';

$styles = get_styles();
$filters = [
    'q'         => isset($_GET['q']) && is_string($_GET['q']) ? mb_substr(trim($_GET['q']), 0, 80) : '',
    'style'     => isset($_GET['style']) && in_array($_GET['style'], $styles, true) ? $_GET['style'] : '',
    'max_price' => isset($_GET['max_price']) ? max(0, (int) $_GET['max_price']) : 0,
    'max_days'  => isset($_GET['max_days']) ? max(0, (int) $_GET['max_days']) : 0,
    'sort'      => isset($_GET['sort']) && is_string($_GET['sort']) ? $_GET['sort'] : '',
];
$packages = get_packages($filters);

$pageTitle = 'Journeys';
$metaDescription = 'Browse small-group and private journeys from Hello Travel.';
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Journeys</p>
        <h1 class="display-lg">Find your journey.</h1>
        <form class="filter-bar" method="get" action="<?= e(url('packages.php')) ?>">
            <label class="field">
                <span class="field-label">Search</span>
                <input type="search" name="q" value="<?= e($filters['q']) ?>" placeholder="Destination or trip" maxlength="80">
            </label>
            <label class="field">
                <span class="field-label">Style</span>
                <select name="style">
                    <option value="">All styles</option>
                    <?php foreach ($styles as $s): ?>
                        <option value="<?= e($s) ?>"<?= $filters['style'] === $s ? ' selected' : '' ?>><?= e($s) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label class="field">
                <span class="field-label">Budget</span>
                <select name="max_price">
                    <option value="">Any budget</option>
                    <?php foreach ([1500, 2500, 4000, 6000] as $b): ?>
                        <option value="<?= $b ?>"<?= $filters['max_price'] === $b ? ' selected' : '' ?>>Up to <?= e(money($b)) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label class="field">
                <span class="field-label">Length</span>
                <select name="max_days">
                    <option value="">Any length</option>
                    <?php foreach ([7, 10, 14] as $dd): ?>
                        <option value="<?= $dd ?>"<?= $filters['max_days'] === $dd ? ' selected' : '' ?>>Up to <?= $dd ?> days</option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label class="field">
                <span class="field-label">Sort</span>
                <select name="sort">
                    <option value="">Recommended</option>
                    <option value="price_asc"<?= $filters['sort'] === 'price_asc' ? ' selected' : '' ?>>Price: low to high</option>
                    <option value="price_desc"<?= $filters['sort'] === 'price_desc' ? ' selected' : '' ?>>Price: high to low</option>
                    <option value="duration"<?= $filters['sort'] === 'duration' ? ' selected' : '' ?>>Shortest first</option>
                </select>
            </label>
            <button class="btn btn-primary" type="submit">Apply</button>
        </form>
    </div>
</section>
<section class="section section-tight">
    <div class="container">
        <p class="result-count mono"><?= count($packages) ?> journey<?= count($packages) === 1 ? '' : 's' ?> found</p>
        <?php if ($packages): ?>
            <div class="card-grid">
                <?php foreach ($packages as $p) { include __DIR__ . '/includes/package-card.php'; } ?>
            </div>
        <?php else: ?>
            <div class="empty">
                <p>No journeys match those filters.</p>
                <a class="btn btn-outline" href="<?= e(url('packages.php')) ?>">Clear filters</a>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
