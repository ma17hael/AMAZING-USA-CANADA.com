<?php

// ✅ CORS (temporaire ou à limiter ensuite)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . '/../../GLOBAL-INCLUDES/CONFIGS/db.php';

$maintenanceMode = true;
$mainUrl = 'http://amazing-usa-canada.local';

try {

    $db = getDB();

    $stmt = $db->query("
        SELECT SettingValue 
        FROM settings 
        WHERE SettingKey = 'maintenance_mode'
        LIMIT 1
    ");

    $value = $stmt->fetchColumn();

    if ($value !== false) {
        $maintenanceMode = ((int)$value === 1);
    }

} catch (Throwable $e) {
    $maintenanceMode = true;
}

echo json_encode([
    'maintenance' => $maintenanceMode,
    'url' => $mainUrl
]);