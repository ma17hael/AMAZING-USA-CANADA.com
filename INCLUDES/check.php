<?php

require_once __DIR__ . '/../../MAINSITE/INCLUDES/init.php';
loadProjectEnv(__DIR__ . '/../../MAINSITE/.env');

$maintenanceMode = getenv('MAINTENANCE_MODE') == 'true';

header('Content-Type: application/json');

echo json_encode([
    'maintenance' => $maintenanceMode,
    'url' => 'http://amazing-usa-canada.local'
]);