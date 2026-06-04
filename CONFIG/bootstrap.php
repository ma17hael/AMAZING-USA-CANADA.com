<?php
require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . '/config.php';

session_start();

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

$router = require __DIR__ . '/routes.php';
$auth = new App\Core\Auth();
$request = new App\Core\Request();
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'], $translator, $router, $auth, $request);