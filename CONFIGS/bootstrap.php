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

$currentHost = $host;

$mainURL = $scheme . '://' . $mainHost;
$maintenanceURL = $scheme . '://' . 'maintenance.' . $mainHost;

/**
 * ======================
 * LANG
 * ======================
 */
$stmt = $db->prepare("SELECT LangCode FROM languages");
$stmt->execute();

$allowedLangs = array_map('strtolower', $stmt->fetchAll(PDO::FETCH_COLUMN));

$lang = $_GET['lang']
    ?? $_COOKIE['lang']
    ?? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'fr', 0, 2)
    ?? 'fr';

$lang = strtolower($lang);

if (!in_array($lang, $allowedLangs)) {
    $lang = 'fr';
}

setcookie('lang', $lang, time() + 3600 * 24 * 30, '/');

$L = loadLang($lang);

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
$isMaintenanceHost = ($currentHost === $maintenanceHost);

if ($maintenance === 1 && !$isMaintenanceHost) {
    header("Location: $maintenanceURL");
    exit;
}

if ($maintenance === 0 && $isMaintenanceHost) {
    header("Location: $mainURL");
    exit;
}