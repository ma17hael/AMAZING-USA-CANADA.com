<?php
require_once __DIR__ . '/../GLOBAL-INCLUDES/CONFIGS/bootstrap.php';

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

$baseUrl = 'http://amazing-usa-canada.local';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <title>Maintenance</title>
    <!-- CSS externe -->
    <link rel="stylesheet" href="CSS/maintenance.css">
    <!-- Google Fonts (optionnel) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>

<body>
    <div class="lang-dropdown">

        <div class="lang-selected" onclick="toggleLang()">
            <img src="<?= $currentFlag ?>" class="flag">
            <span><?= $currentLangName ?></span>
        </div>

        <div class="lang-list" id="langList">

            <?php foreach ($langs as $l): ?>
                <a href="?lang=<?= $l['code'] ?>&r=1" class="lang-item">

                    <img src="<?= $l['flag'] ?>" class="flag">
                    <span><?= $l['name'] ?></span>

                </a>
            <?php endforeach; ?>

        </div>

    </div>

    <div class="container">
        <img src="ASSETS/CANADALogo.webp" class="logo" alt="logo">

        <h1><?= t('maintenance_title') ?></h1>
        <p><?= t('maintenance_text') ?></p>
    </div>


</body>
<script>
    async function checkMaintenance() {
        try {
            const res = await fetch('/INCLUDES/check.php', {
                cache: "no-store"
            });
            const data = await res.json();

            if (!data.maintenance) {
                window.location.href = '<?= $baseUrl ?>';
            }
        } catch (e) {
            console.log("Check maintenance failed");
        }
    }

    setInterval(checkMaintenance, 10000);

    function toggleLang() {
        document.getElementById('langList').classList.toggle('show');
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.lang-dropdown')) {
            document.getElementById('langList').classList.remove('show');
        }
    });
</script>

</html>