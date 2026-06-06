<?php
/** @var App\Services\TranslationService $translator */
/** @var App\Core\Router $router */
/** @var App\Core\Auth $auth */

$lang = $router->getLang();
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= \App\Core\Csrf::token() ?>">
    <title><?= $translator->safe('home.title') ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;1,400&family=Source+Sans+3:wght@400;600&display=swap" rel="stylesheet">

    <!-- Icônes -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <!-- CSS Locaux -->
    <link rel="stylesheet" href="<?= APP_URL_ASSETS ?>/CSS/global.css">
    <link rel="stylesheet" href="<?= APP_URL_ASSETS ?>/CSS/app.css">
</head>
<body class="bg-dark">
    <section class="hero">
        <div class="hero-inner">
            <!-- Partie Gauche du Hero -->
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div>
                    <div class="hero-subtitle"><?= $translator->safe('home.welcome_sub') ?></div>
                    <div class="hero-title"><?= $translator->safe('home.title') ?></div>
                </div>
                <div class="hero-stars">
                    <div class="star"></div>
                    <div class="star"></div>
                    <div class="star"></div>
                </div>
                <div class="hero-box">
                    <div class="hero-box-icon">
                        <i class="ti ti-compass"></i>
                    </div>
                    <div>
                        <div class="hero-box-title"><?= $translator->safe('home.box_title') ?></div>
                        <div class="hero-box-desc"><?= $translator->safe('home.box_desc') ?></div>
                    </div>
                </div>
                <a href="/<?= $lang ?>/cartes" class="btn-primary">
                    <i class="ti ti-map"></i>
                    <?= $translator->safe('home.cta') ?>
                </a>
            </div>

            <!-- Partie Droite du Hero -->
             <div class="hero-map">
                <div style="position:absolute; top: 12px; right: 12px; display: flex; gap: 4px; font-size: 20px;">
                    🇺🇸 🇨🇦
                </div>
                <div style="color:rgba(255, 255, 255, 0.3); text-align: center;">
                    <i class="ti ti-map-2" style="font-size: 48px;"></i>
                    <p style="font-size: 12px; margin-top:8px;"><?= $translator->safe('home.map_label') ?></p>
                </div>
                <div style="position:absolute; bottom: 20px; left: 20px; display:flex; align-items: center; gap:6px;">
                    <i class="ti ti-map-pin" style="color:#f0c060; font-size:20px;"></i>
                    <div style="width:60px; height:1px; background:rgba(255,255,255,0.2);"></div>
                </div>
             </div>
        </div>
    </section>
    <div class="features-bar">
        <div class="feature">
            <div class="feature-icon"><i class="ti ti-map-2"></i></div>
            <div>
                <div class="feature-label"><?= $translator->safe('feat.maps') ?></div>
                <div class="feature-sub"><?= $translator->safe('feat.maps_sub') ?></div>
            </div>
        </div>
        <div class="feature">
            <div class="feature-icon"><i class="ti ti-diamond"></i></div>
            <div>
                <div class="feature-label"><?= $translator->safe('feat.sites') ?></div>
                <div class="feature-sub"><?= $translator->safe('feat.sites_sub') ?></div>
            </div>
        </div>
        <div class="feature">
            <div class="feature-icon"><i class="ti ti-mountain"></i></div>
            <div>
                <div class="feature-label"><?= $translator->safe('feat.landscapes') ?></div>
                <div class="feature-sub"><?= $translator->safe('feat.landscapes_sub') ?></div>
            </div>
        </div>
        <div class="feature">
            <div class="feature-icon"><i class="ti ti-building-arch"></i></div>
            <div>
                <div class="feature-label"><?= $translator->safe('feat.history') ?></div>
                <div class="feature-sub"><?= $translator->safe('feat.history_sub') ?></div>
            </div>
        </div>
        <div class="feature">
            <div class="feature-icon"><i class="ti ti-leaf"></i></div>
            <div>
                <div class="feature-label"><?= $translator->safe('feat.nature') ?></div>
                <div class="feature-sub"><?= $translator->safe('feat.nature_sub') ?></div>
            </div>
        </div>
    </div>
</body>
</html>