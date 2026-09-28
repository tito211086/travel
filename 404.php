<?php
require_once __DIR__ . '/includes/bootstrap.php';
http_response_code(404);
$pageTitle = 'Page not found';
require __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container narrow">
        <p class="eyebrow">Error 404</p>
        <h1 class="display-lg">Off the map.</h1>
        <p class="lede">We couldn't find that page. It may have moved, or the link might be wrong.</p>
        <a class="btn btn-primary" href="<?= e(url('index.php')) ?>">Back to home</a>
        <a class="btn btn-outline" href="<?= e(url('packages.php')) ?>">Browse journeys</a>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
