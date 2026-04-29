<?php

require_once __DIR__ . '/../GLOBAL-INCLUDES/CONFIGS/db.php';

$mainUrl = 'http://amazing-usa-canada.local';
$maintenanceMode = true;

try {

    $db = getDB();

    $stmt = $db->prepare("
        SELECT SettingValue 
        FROM settings 
        WHERE SettingKey = :key 
        LIMIT 1
    ");

    $stmt->execute(['key' => 'maintenance_mode']);

    $value = $stmt->fetchColumn();

    if ($value !== false) {
        $maintenanceMode = ((int)$value === 1);
    }
} catch (Throwable $e) {

    error_log("Maintenance page error: " . $e->getMessage());
    $maintenanceMode = true;
}

// si maintenance OFF → retour site
if (!$maintenanceMode) {
    header("Location: $mainUrl");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Maintenance</title>
    <!-- CSS externe -->
    <link rel="stylesheet" href="CSS/maintenance.css">
    <!-- Google Fonts (optionnel) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>

<body>

    <div class="container">
        <img src="ASSETS/CANADALogo.webp" class="logo" alt="logo">

        <h1>🚧 Site en maintenance</h1>
        <p>On améliore l'expérience, reviens vite 😉</p>
    </div>

</body>
<script>
    setInterval(() => {
        fetch('/INCLUDES/check.php')
            .then(res => res.json())
            .then(data => {
                if (!data.maintenance) {
                    window.location.href = data.url;
                }
            })
            .catch(() => {
                console.log("Check maintenance failed");
            });
    }, 5000);
</script>
</html>