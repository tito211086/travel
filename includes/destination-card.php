<?php /** @var array $d */ ?>
<a class="dest-card" href="<?= e(url('destination.php', ['slug' => $d['slug']])) ?>" style="--tile: <?= e($d['accent_color']) ?>">
    <?php if (!empty($d['image_url'])): ?>
        <img src="<?= e($d['image_url']) ?>" alt="" loading="lazy">
    <?php endif; ?>
    <span class="dest-meta mono"><?= e($d['region']) ?></span>
    <span class="dest-name"><?= e($d['name']) ?></span>
    <span class="dest-foot"><span><?= e($d['country']) ?></span><span><?= (int) $d['package_count'] ?> journey<?= (int) $d['package_count'] === 1 ? '' : 's' ?> →</span></span>
</a>
