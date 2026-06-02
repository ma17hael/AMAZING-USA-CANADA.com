<?php
require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . '/config.php';

session_start();

$settings = new App\Services\SettingService();
$settings->preloadAll();

$maintenance = new App\Services\MaintenanceService($settings);
$maintenance->check();

$translator = new App\Services\TranslationService();
$translator->detect(urlLang: $_GET['lang'] ?? null, userLang: $user->langCode ?? null, sessionLang: $_SESSION['lang'] ?? null);