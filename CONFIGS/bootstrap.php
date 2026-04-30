<?php

define('ROOT_PATH', realpath(__DIR__ . '/../..'));

require_once ROOT_PATH . '/GLOBAL-INCLUDES/CONFIGS/db.php';
require_once ROOT_PATH . '/GLOBAL-INCLUDES/CONFIGS/lang.php';

$db = getDB();

/**
 * ======================
 * URL ENV
 * ======================
 */
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];

$mainHost = preg_replace('/^maintenance\./', '', $host);
$maintenanceHost = 'maintenance.' . $mainHost;

$mainURL = $scheme . '://' . $mainHost;
$maintenanceURL = $scheme . '://' . $maintenanceHost;

/**
 * ======================
 * LANG LIST FROM DB
 * ======================
 */
$stmt = $db->prepare("SELECT LangCode FROM languages");
$stmt->execute();

$allowedLangs = array_map('strtolower', $stmt->fetchAll(PDO::FETCH_COLUMN));

/**
 * ======================
 * LANG PRIORITY SYSTEM
 * ======================
 * 1. GET (user change)
 * 2. COOKIE (persist)
 * 3. BROWSER
 */
$lang = $_GET['lang']
    ?? $_COOKIE['lang']
    ?? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'fr-FR', 0, 5);

$lang = strtolower($lang);

/**
 * ======================
 * VALIDATION
 * ======================
 */
if (!in_array($lang, $allowedLangs)) {
    // fallback intelligent
    $lang = 'fr-fr';
}

/**
 * ======================
 * COOKIE SAVE
 * ======================
 */
setcookie('lang', $lang, time() + 3600 * 24 * 30, '/');

/**
 * ======================
 * LOAD LANG FILES
 * ======================
 */
$L = loadLang($lang);

$langs = getAvailableLanguages($db);

$current = null;
foreach ($langs as $l) {
    if (strtolower($l['code']) === strtolower($lang)) {
        $current = $l;
        break;
    }
}
if (!$current) {
    foreach ($langs as $l) {
        if (explode('-', strtolower($l['code']))[0] === explode('-', strtolower($lang))[0]) {
            $current = $l;
            break;
        }
    }
}
if (!$current) {
    $current = $langs[0];
}

$currentFlag = $current['flag'];
$currentLangName = $current['name'];

/**
 * ======================
 * MAINTENANCE CHECK
 * ======================
 */
$stmt = $db->prepare("
    SELECT SettingValue 
    FROM settings 
    WHERE SettingKey = 'maintenance_mode'
    LIMIT 1
");
$stmt->execute();

$maintenance = (int)$stmt->fetchColumn();

/**
 * ======================
 * ROUTING SAFE
 * ======================
 */
$isMaintenanceHost = str_starts_with($host, 'maintenance.');

if ($maintenance === 1 && !$isMaintenanceHost) {
    header("Location: $maintenanceURL");
    exit;
}

if ($maintenance === 0 && $isMaintenanceHost) {
    header("Location: $mainURL");
    exit;
}

/**
 * ======================
 * GLOBAL VARS
 * ======================
 */
$langRaw = $lang;
$globalLang = explode('-', $lang)[0];