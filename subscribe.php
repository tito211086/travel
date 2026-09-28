<?php
require __DIR__ . '/includes/bootstrap.php';

$return = isset($_POST['return']) && is_string($_POST['return']) && str_starts_with($_POST['return'], '/')
    && !str_starts_with($_POST['return'], '//') ? $_POST['return'] : url('index.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(url('index.php'));
}
if (!csrf_check()) {
    flash('error', 'Your session expired. Please try again.');
    redirect($return);
}
if (is_bot()) {
    flash('success', 'You\'re subscribed. Welcome aboard!');
    redirect($return);
}

$email = post_str('email', 190);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flash('error', 'Please enter a valid email address.');
    redirect($return);
}

try {
    db()->prepare('INSERT INTO subscribers (email, created_at) VALUES (?, ?)')
        ->execute([mb_strtolower($email), date('Y-m-d H:i:s')]);
} catch (PDOException $e) {
    if ($e->getCode() !== '23000') { // 23000 = already subscribed (unique email)
        throw $e;
    }
}

flash('success', 'You\'re subscribed. Welcome aboard!');
redirect($return);
