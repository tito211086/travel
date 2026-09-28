<?php
require __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'About';
$metaDescription = 'Meet Hello Travel: a small team designing thoughtful journeys since 2012.';
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">About us</p>
        <h1 class="display-lg">Travel with <span class="accent">intention</span>.</h1>
        <p class="lede">We started Hello Travel in 2012 with a simple idea: the best trips come from real local knowledge, unhurried time and small groups.</p>
    </div>
</section>
<section class="section section-tight">
    <div class="container split">
        <div class="art-panel" aria-hidden="true"><div class="shape shape-sun"></div><div class="shape shape-block"></div></div>
        <div class="prose">
            <h2 class="h-section">Our story</h2>
            <p>What began as two friends guiding walking tours has grown into a team of thirty travel designers and more than two hundred local guides across six continents.</p>
            <p>We're still small on purpose. Every itinerary is tested by our team before it goes on sale, and every traveller gets a named specialist from first idea to safe return home.</p>
            <h2 class="h-section">What we believe</h2>
            <ul class="check-list">
                <li>Local guides, locally owned stays and fair pay for everyone on the ground.</li>
                <li>Groups of twelve or fewer — always.</li>
                <li>Carbon-balanced departures and plastic-free trips.</li>
                <li>Honest pricing with no hidden extras.</li>
            </ul>
        </div>
    </div>
</section>
<section class="section section-alt">
    <div class="container">
        <ul class="stat-row stat-row-lg">
            <li><strong>2012</strong><span>Founded</span></li>
            <li><strong>48</strong><span>Countries</span></li>
            <li><strong>200+</strong><span>Local guides</span></li>
            <li><strong>12k+</strong><span>Travellers</span></li>
        </ul>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="cta-block">
            <div><h2 class="display-md">Ready when you are.</h2><p class="lede">Start with a conversation — we'll take it from there.</p></div>
            <a class="btn btn-light btn-lg" href="<?= e(url('contact.php')) ?>">Talk to us</a>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
