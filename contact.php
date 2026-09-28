<?php
require __DIR__ . '/includes/bootstrap.php';

$errors = [];
$sent = false;
$data = [
    'name'        => '',
    'email'       => '',
    'phone'       => '',
    'destination' => isset($_GET['destination']) && is_string($_GET['destination']) ? mb_substr($_GET['destination'], 0, 120) : '',
    'budget'      => '',
    'message'     => '',
];
$budgets = ['Under $2,000', '$2,000 – $4,000', '$4,000 – $7,000', '$7,000+', 'Not sure yet'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['name' => 120, 'email' => 190, 'phone' => 40, 'destination' => 120, 'budget' => 40, 'message' => 3000] as $k => $max) {
        $data[$k] = post_str($k, $max);
    }

    if (!csrf_check()) {
        $errors['form'] = 'Your session expired. Please submit the form again.';
    }
    if ($data['name'] === '') {
        $errors['name'] = 'Please tell us your name.';
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }
    if ($data['budget'] !== '' && !in_array($data['budget'], $budgets, true)) {
        $errors['budget'] = 'Please choose a budget from the list.';
    }
    if (mb_strlen($data['message']) < 10) {
        $errors['message'] = 'Tell us a little more (at least 10 characters).';
    }

    if (!$errors) {
        if (!is_bot()) {
            db()->prepare('INSERT INTO contact_messages (name, email, phone, destination, budget, message, ip_address, created_at)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)')
                ->execute([
                    $data['name'], $data['email'], $data['phone'] ?: null, $data['destination'] ?: null,
                    $data['budget'] ?: null, $data['message'], $_SERVER['REMOTE_ADDR'] ?? null, date('Y-m-d H:i:s'),
                ]);
        }
        flash('success', 'Thanks, ' . $data['name'] . '! We\'ve received your message and will reply within one business day.');
        redirect(url('contact.php'));
    }
}

function field_error(array $errors, string $key): string
{
    return isset($errors[$key]) ? '<span class="field-error" id="err-' . e($key) . '">' . e($errors[$key]) . '</span>' : '';
}

$pageTitle = 'Contact';
$metaDescription = 'Talk to a Hello Travel specialist and start planning your journey.';
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Contact</p>
        <h1 class="display-lg">Let's plan something great.</h1>
        <p class="lede">Share a few details and a travel designer will get back to you within one business day.</p>
    </div>
</section>
<section class="section section-tight">
    <div class="container contact-grid">
        <form method="post" action="<?= e(url('contact.php')) ?>" class="stack-form card-form" novalidate data-validate>
            <?php if (isset($errors['form'])): ?><div class="flash-inline"><?= e($errors['form']) ?></div><?php endif; ?>
            <?= csrf_field() ?>
            <div class="field-row">
                <label class="field<?= isset($errors['name']) ? ' has-error' : '' ?>"><span class="field-label">Full name</span>
                    <input type="text" name="name" required maxlength="120" value="<?= e($data['name']) ?>"<?= isset($errors['name']) ? ' aria-invalid="true" aria-describedby="err-name"' : '' ?>>
                    <?= field_error($errors, 'name') ?></label>
                <label class="field<?= isset($errors['email']) ? ' has-error' : '' ?>"><span class="field-label">Email</span>
                    <input type="email" name="email" required maxlength="190" value="<?= e($data['email']) ?>"<?= isset($errors['email']) ? ' aria-invalid="true" aria-describedby="err-email"' : '' ?>>
                    <?= field_error($errors, 'email') ?></label>
            </div>
            <div class="field-row">
                <label class="field"><span class="field-label">Phone <small>(optional)</small></span>
                    <input type="tel" name="phone" maxlength="40" value="<?= e($data['phone']) ?>"></label>
                <label class="field"><span class="field-label">Destination <small>(optional)</small></span>
                    <input type="text" name="destination" maxlength="120" value="<?= e($data['destination']) ?>" placeholder="e.g. Japan"></label>
            </div>
            <label class="field<?= isset($errors['budget']) ? ' has-error' : '' ?>"><span class="field-label">Budget per person</span>
                <select name="budget">
                    <option value="">Select a budget</option>
                    <?php foreach ($budgets as $b): ?>
                        <option<?= $data['budget'] === $b ? ' selected' : '' ?>><?= e($b) ?></option>
                    <?php endforeach; ?>
                </select>
                <?= field_error($errors, 'budget') ?></label>
            <label class="field<?= isset($errors['message']) ? ' has-error' : '' ?>"><span class="field-label">Tell us about your trip</span>
                <textarea name="message" rows="6" required minlength="10" maxlength="3000"<?= isset($errors['message']) ? ' aria-invalid="true" aria-describedby="err-message"' : '' ?>><?= e($data['message']) ?></textarea>
                <?= field_error($errors, 'message') ?></label>
            <div class="hp" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
            <button class="btn btn-primary" type="submit">Send message</button>
        </form>
        <aside class="contact-info">
            <div class="info-block"><p class="eyebrow">Call</p><a href="tel:<?= e(preg_replace('/[^0-9+]/', '', SITE_PHONE)) ?>"><?= e(SITE_PHONE) ?></a><span>Mon–Fri, 9:00–18:00</span></div>
            <div class="info-block"><p class="eyebrow">Email</p><a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a><span>Replies within 1 business day</span></div>
            <div class="info-block"><p class="eyebrow">Visit</p><span>210 Harbour Street<br>Studio 4, Portside</span></div>
        </aside>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
