<?php
require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . '/config.php';

if(APP_DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', APP_ENV === 'production' ? 1 : 0);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_strict_mode', 1);
ini_set('session.gc_maxlifetime', 3600);

session_start();

header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: camera=(), microphone=(), geolocation=()");

if (APP_ENV === 'production') {
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
    header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;");
} else {
    header("Content-Security-Policy: default-src * 'unsafe-inline' 'unsafe-eval' data:;");
}

use App\Core\Csrf;
Csrf::generate();

App\Core\Logger::info('Requête reçue', [
    'method' => $_SERVER['REQUEST_METHOD'],
    'uri' => $_SERVER['REQUEST_URI'],
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
]);

$settings = new App\Services\SettingService();
$settings->preloadAll();

$maintenance = new App\Services\MaintenanceService($settings);
$maintenance->check();

$translator = new App\Services\TranslationService();
$request = new App\Core\Request();
$auth = new App\Core\Auth();

$router = require __DIR__ . '/routes.php';
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'], $translator, $router, $auth, $request);