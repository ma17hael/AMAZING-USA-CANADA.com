<?php
require_once __DIR__ . '/../../GLOBAL-INCLUDES/CONFIGS/bootstrap.php';

$db = getDB();

$menu = MegaMenuService::get($db, $langID, $currentFallback);

$countries = array_values(is_array($menu['countries'] ?? null) ? $menu['countries'] : []);
$locations = array_values(is_array($menu['locations'] ?? null) ? $menu['locations'] : []);

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<header class="site-header">
    <div class="header-container">

        <div class="logo">
            <img src="http://assets.amazing-usa-canada.local/IMAGES/AMERICALogo.webp" alt="Amazing USA Canada">
            <span>AMAZING-USA-CANADA.COM</span>
        </div>

        <nav class="nav">
            <div class="nav-item">
                <a href="#" class="<?= $currentPage == 'index.php' ? 'active' : '' ?>"><?= t('home') ?></a>
            </div>
            <div class="nav-item mega-parent">
                <a href="#" class=""><?= t('available_maps') ?></a>
                <div class="mega-menu">
                    <div class="mega-section">
                        <h4><?= t('countries') ?></h4>
                        <?php foreach ($countries as $country): ?>
                            <a href="#">
                                <?= htmlspecialchars($country['Name'] ?? $country['ISOCode'] ?? ''); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <div class="mega-section">
                        <h4><?= t('type') ?></h4>
                        <a href="#"><?= t('standard') ?></a>
                        <a href="#"><?= t('free') ?></a>
                        <a href="#"><?= t('card_pack') ?></a>
                    </div>
                    <div class="mega-section">
                        <h4><?= t('location') ?></h4>
                        <?php foreach ($locations as $location): ?>
                            <a href="#">
                                <?= htmlspecialchars($location['Label'] ?? '') ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="nav-item">
                <a href="#"><?= t('gallery') ?></a>
            </div>
            <div class="nav-item">
                <a href="#"><?= t('forum') ?></a>
            </div>
            <div class="nav-item">
                <a href="#"><?= t('contact') ?></a>
            </div>
        </nav>

        <div class="header-actions">
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
            <a href="#" class="btn-login"><?= t('login') ?></a>
            <a href="#" class="btn-primary"><?= t('register') ?></a>
        </div>

    </div>
</header>