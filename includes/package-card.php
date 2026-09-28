<?php /** @var array $p */ ?>
<article class="card package-card">
    <a class="card-media" href="<?= e(url('package.php', ['slug' => $p['slug']])) ?>" style="--tile: <?= e($p['accent_color'] ?? '#1d3cff') ?>">
        <?php if (!empty($p['image_url'])): ?>
            <img src="<?= e($p['image_url']) ?>" alt="<?= e($p['title']) ?>" loading="lazy">
        <?php else: ?>
            <span class="tile-label" aria-hidden="true"><?= e($p['destination_name']) ?></span>
        <?php endif; ?>
        <span class="badge"><?= e($p['style']) ?></span>
    </a>
    <div class="card-body">
        <p class="card-kicker mono"><?= e($p['destination_name']) ?>, <?= e($p['country']) ?> · <?= (int) $p['duration_days'] ?> days</p>
        <h3 class="card-title"><a href="<?= e(url('package.php', ['slug' => $p['slug']])) ?>"><?= e($p['title']) ?></a></h3>
        <p class="card-text"><?= e($p['summary']) ?></p>
        <div class="card-foot">
            <span class="price"><small>from</small> <?= e(money((float) $p['price'], $p['currency'])) ?></span>
            <?= stars((float) $p['rating']) ?>
        </div>
    </div>
</article>
