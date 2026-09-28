<?php
$pageTitle = isset($pageTitle) ? $pageTitle . ' — ' . SITE_NAME : SITE_NAME . ' — ' . SITE_TAGLINE;
$metaDescription = $metaDescription ?? 'Hello Travel designs small-group and tailor-made journeys to the world\'s most remarkable places.';
$nav = [
    'index.php'        => 'Home',
    'destinations.php' => 'Destinations',
    'packages.php'     => 'Journeys',
    'about.php'        => 'About',
    'contact.php'      => 'Contact',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <meta name="theme-color" content="#111111">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' fill='%23111'/%3E%3Ccircle cx='22' cy='10' r='5' fill='%23ff4d2e'/%3E%3C/svg%3E">
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>
<header class="site-header" data-header>
    <div class="container header-inner">
        <a class="brand" href="<?= e(url('index.php')) ?>" aria-label="<?= e(SITE_NAME) ?> home">
            <span class="brand-mark" aria-hidden="true"></span>
            <span class="brand-name">Hello<span>Travel</span></span>
        </a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
            <span class="sr-only">Menu</span><span class="bar"></span><span class="bar"></span>
        </button>
        <nav id="site-nav" class="site-nav" data-nav>
            <ul>
                <?php foreach ($nav as $href => $label): ?>
                    <li><a href="<?= e(url($href)) ?>"<?= is_current($href) ? ' aria-current="page"' : '' ?>><?= e($label) ?></a></li>
                <?php endforeach; ?>
            </ul>
            <a class="btn btn-primary btn-sm" href="<?= e(url('contact.php')) ?>">Plan a trip</a>
        </nav>
    </div>
</header>
<main id="main">
<?php foreach (take_flashes() as $f): ?>
    <div class="flash flash-<?= e($f['type']) ?>" role="status">
        <div class="container"><?= e($f['message']) ?></div>
    </div>
<?php endforeach; ?>
