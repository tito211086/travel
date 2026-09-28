<?php
require __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(url('packages.php'));
}

$packageId = (int) ($_POST['package_id'] ?? 0);
$st = db()->prepare('SELECT id, slug, group_size FROM packages WHERE id = ? AND is_active = 1');
$st->execute([$packageId]);
$package = $st->fetch();
if (!$package) {
    redirect(url('packages.php'));
}
$back = url('package.php', ['slug' => $package['slug']]) . '#enquire';

if (!csrf_check()) {
    flash('error', 'Your session expired. Please submit the form again.');
    redirect($back);
}
if (is_bot()) {
    flash('success', 'Thanks! Your enquiry has been sent.');
    redirect($back);
}

$data = [
    'name'        => post_str('name', 120),
    'email'       => post_str('email', 190),
    'phone'       => post_str('phone', 40),
    'travel_date' => post_str('travel_date', 10),
    'travellers'  => (int) ($_POST['travellers'] ?? 1),
    'message'     => post_str('message', 2000),
];
$_SESSION['old'] = $data;

$errors = [];
if ($data['name'] === '') {
    $errors[] = 'Please enter your name.';
}
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
if ($data['travellers'] < 1 || $data['travellers'] > (int) $package['group_size']) {
    $errors[] = 'Number of travellers must be between 1 and ' . (int) $package['group_size'] . '.';
}
$date = null;
if ($data['travel_date'] !== '') {
    $dt = DateTime::createFromFormat('!Y-m-d', $data['travel_date']);
    if (!$dt || $dt->format('Y-m-d') !== $data['travel_date'] || $dt < new DateTime('today')) {
        $errors[] = 'Please choose a valid future departure date.';
    } else {
        $date = $data['travel_date'];
    }
}

if ($errors) {
    flash('error', implode(' ', $errors));
    redirect($back);
}

$st = db()->prepare('INSERT INTO enquiries (package_id, name, email, phone, travel_date, travellers, message, ip_address, created_at)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
$st->execute([
    $package['id'], $data['name'], $data['email'], $data['phone'] ?: null, $date,
    $data['travellers'], $data['message'] ?: null, $_SERVER['REMOTE_ADDR'] ?? null, date('Y-m-d H:i:s'),
]);

clear_old();
flash('success', 'Thanks, ' . $data['name'] . '! Your enquiry is in — a travel specialist will be in touch within one business day.');
redirect($back);
