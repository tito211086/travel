<?php
/**
 * Site configuration.
 * Values can be overridden with environment variables (useful on shared hosting / Docker).
 */

define('SITE_NAME', 'Hello Travel');
define('SITE_TAGLINE', 'Journeys, thoughtfully made.');
define('SITE_EMAIL', getenv('SITE_EMAIL') ?: 'hello@hellotravel.example');
define('SITE_PHONE', getenv('SITE_PHONE') ?: '+1 (555) 010-2030');
define('BASE_URL', rtrim(getenv('BASE_URL') ?: '', '/'));

define('DB_DSN',  getenv('DB_DSN')  ?: 'mysql:host=' . (getenv('DB_HOST') ?: '127.0.0.1')
    . ';port=' . (getenv('DB_PORT') ?: '3306')
    . ';dbname=' . (getenv('DB_NAME') ?: 'hello_travel')
    . ';charset=utf8mb4');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

define('APP_DEBUG', getenv('APP_DEBUG') === '1');

date_default_timezone_set('UTC');
