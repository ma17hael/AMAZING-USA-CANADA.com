<?php 

require_once __DIR__ . '/../MAINSITE/INCLUDES/init.php';
loadProjectEnv(__DIR__ . '/../MAINSITE/.env');

$maintenanceMode = getenv('MAINTENANCE_MODE') == 'true';
$mainUrl = 'http://amazing-usa-canada.local';

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
            });
    }, 5000)
</script>
</html>