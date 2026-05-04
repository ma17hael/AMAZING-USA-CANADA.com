<?php

define('ROOT_PATH', realpath(__DIR__ . '/../..'));

require_once ROOT_PATH . '/GLOBAL-INCLUDES/CONFIGS/db.php';
require_once ROOT_PATH . '/GLOBAL-INCLUDES/CONFIGS/lang.php';
require_once ROOT_PATH . '/GLOBAL-INCLUDES/CONFIGS/cache.php';

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

$allowedLangsRaw = $stmt->fetchAll(PDO::FETCH_COLUMN);

/**
 * Normalisation propre (case + format uniforme)
 */
$allowedLangs = array_map(
    fn($l) => strtolower(str_replace('_', '-', $l)),
    $allowedLangsRaw
);

$stmt = $db->prepare("SELECT LanguageID, LangCode FROM languages");
$stmt->execute();

$langMap = [];
$reverseLangMap = [];

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {

    $code = strtolower(str_replace('_', '-', $row['LangCode']));

    $langMap[$code] = (int)$row['LanguageID'];
    $reverseLangMap[(int)$row['LanguageID']] = $code;
}

/**
 * ======================
 * LANG PRIORITY SYSTEM
 * ======================
 * 1. GET
 * 2. COOKIE
 * 3. BROWSER
 */
$accept = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'fr-FR';

preg_match('/[a-zA-Z]{2}-[a-zA-Z]{2}/', $accept, $m);
$browserLang = isset($m[0]) ? strtolower($m[0]) : 'fr-fr';

$lang = $_GET['lang']
    ?? $_COOKIE['lang']
    ?? $browserLang;

$lang = strtolower(str_replace('_', '-', $lang));

/**
 * ======================
 * VALIDATION + FALLBACK
 * ======================
 */
if (!in_array($lang, $allowedLangs)) {

    $base = explode('-', $lang)[0];

    $match = null;

    foreach ($allowedLangs as $l) {
        if (str_starts_with($l, $base . '-')) {
            $match = $l;
            break;
        }
    }

    $lang = $match ?? 'fr-fr';
}

/**
 * ======================
 * COOKIE SAVE (SECURE)
 * ======================
 */
setcookie('lang', $lang, [
    'expires' => time() + 3600 * 24 * 30,
    'path' => '/',
    'secure' => !empty($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);

/**
 * ======================
 * LOAD LANG FILES
 * ======================
 */
$L = loadLang($lang);

/**
 * ======================
 * LANG META FROM DB
 * ======================
 */
$langs = getAvailableLanguages($db);

/**
 * ======================
 * CURRENT LANG (OPTIMIZED)
 * ======================
 */
$current = null;
$baseLang = explode('-', $lang)[0];

foreach ($langs as $l) {

    $code = strtolower($l['code']);

    if ($code === $lang) {
        $current = $l;
        break;
    }

    if (!$current && str_starts_with($code, $baseLang)) {
        $current = $l;
    }
}

$current ??= $langs[0];

/**
 * ======================
 * CURRENT VARS
 * ======================
 */
$currentFlag = $current['flag'];
$currentLangName = $current['name'];
$currentFallback = $current['fallbackID'];

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
$globalLang = $baseLang;
$langID = $langMap[$lang] ?? $langMap['fr-fr'];
$baseLangID = $langMap[$globalLang] ?? $langID;