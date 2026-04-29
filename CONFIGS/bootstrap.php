<?php
// ======================
// CONFIGURATION
// ======================

define('ROOT_PATH', realpath(__DIR__ . '/../..'));

require_once ROOT_PATH . '/GLOBAL-INCLUDES/CONFIGS/db.php';

// ======================
// DB CONNECTION
// ======================
$db = getDB();

// ======================
// MAINTENANCE CHECK
// ======================
$stmt = $db->prepare("
    SELECT SettingValue 
    FROM settings 
    WHERE SettingKey = 'maintenance_mode'
    LIMIT 1
");

$stmt->execute();
$maintenance = (int)$stmt->fetchColumn();

// ======================
// REDIRECTION AUTO
// ======================
if ($maintenance === 1) {

    if (!str_contains($_SERVER['REQUEST_URI'], 'MAINTENANCE')) {
        header("Location: http://amazing-usa-canada.local");
        exit;
    }
}