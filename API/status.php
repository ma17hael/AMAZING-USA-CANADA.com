<?php
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: http://maintenance.amazing-usa-canada.local:8080');

echo json_encode(['online' => true]);