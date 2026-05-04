<?php
require_once __DIR__ . '/../GLOBAL-INCLUDES/CONFIGS/bootstrap.php';

$baseUrl = 'http://amazing-usa-canada.local';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <title>Maintenance</title>
    <link rel="stylesheet" href="http://assets.amazing-usa-canada.local/CSS/global.css">
    <link rel="stylesheet" href="CSS/maintenance.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>

    <!-- LANG SWITCH -->
    <div class="lang-dropdown">
        <div class="lang-selected" onclick="toggleLang()">
            <img src="<?= $currentFlag ?>" class="flag">
            <span><?= $currentLangName ?></span>
        </div>

        <div class="lang-list" id="langList">
            <?php foreach ($langs as $l): ?>
                <a href="?lang=<?= $l['code'] ?>" class="lang-item">
                    <img src="<?= $l['flag'] ?>" class="flag">
                    <span><?= $l['name'] ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- MAIN -->
    <div class="maintenance-wrapper">

        <div class="maintenance-card">

            <span class="material-symbols-outlined icon fade-in delay-1">construction</span>

            <h1 class="title fade-in delay-2"><?= t('maintenance_title') ?></h1>

            <div class="status-pill status-main fade-in delay-3">
                <span class="status-dot"></span>
                <?= t('maintenance_status') ?>
            </div>

            <p class="subtitle fade-in delay-4">
                <?= t('maintenance_text') ?>
            </p>

            <div class="brand-line fade-in delay-5">
                <?= t('maintenance_brand_line') ?>
            </div>

        </div>

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
        } catch (e) {}
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