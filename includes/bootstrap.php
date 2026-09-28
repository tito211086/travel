<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

error_reporting(E_ALL);
ini_set('display_errors', APP_DEBUG ? '1' : '0');

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
    session_start();
}

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');

set_exception_handler(function (Throwable $e) {
    error_log((string) $e);
    http_response_code(500);
    $message = APP_DEBUG ? $e->getMessage() : 'Something went wrong on our side. Please try again shortly.';
    $pageTitle = 'Error';
    require __DIR__ . '/header.php';
    echo '<section class="section"><div class="container narrow"><p class="eyebrow">Error 500</p><h1 class="display-md">We hit some turbulence.</h1><p class="lede">' . e($message) . '</p><a class="btn btn-primary" href="' . url('index.php') . '">Back to home</a></div></section>';
    require __DIR__ . '/footer.php';
});
