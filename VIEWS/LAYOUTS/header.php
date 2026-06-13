<?php
/** @var App\Services\TranslationService $translator */
/** @var App\Core\Router $router */
/** @var App\Core\Auth $auth */

$lang = $router->getLang();
$currentPath = $router->getCurrentPath();
$cartCount = 0;
?>

<!-- Bandeau promo -->
<div class="header-promo">
    <i class="ti ti-star-filled" aria-hidden="true"></i>
    <?= $translator->safe('promo.text') ?>
    <i class="ti ti-star-filled" aria-hidden="true"></i>
</div>

<header class="header">
    <div class="header-inner">
        <!-- Logo -->
        <a href="/<?= $lang ?>/" class="header-logo">
            <div class="logo-icon">
                <i class="ti ti-map-2" aria-hidden="true"></i>
            </div>
            <div class="logo-text">
                <span class="logo-main"><?= $translator->safe('site.name') ?></span>
                <span class="logo-sub"><?= $translator->safe('site.tagline') ?></span>
            </div>
        </a>

        <!-- Navigation -->
        <nav class="header-nav" aria-label="<?= $translator->safe('nav.aria_label') ?>">
            <a href="/<?= $lang ?>/"
               class="nav-link <?= $currentPath === '/' ? 'active' : '' ?>">
               <i class="ti ti-home" aria-hidden="true"></i>
               <?= $translator->safe('nav.home') ?>
            </a>
            
            <!-- Méga-menu cartes -->
            <?php require __DIR__ . '/PARTIALS/mega-menu.php'; ?>

            <div class="nav-sep" aria-hidden="true"></div>

            <a href="/<?= $lang ?>/contact"
               class="nav-link <?= str_starts_with($currentPath, '/contact') ? 'active' : '' ?>">
               <i class="ti ti-message" aria-hidden="true"></i>
               <?= $translator->safe('nav.contact') ?>
            </a> 
        </nav>

        <!-- Droite -->
        <div class="header-right">
            <!-- Langue -->
            <div class="lang-selector" role="group"
                 aria-label="<?= $translator->safe('lang.selector.label') ?>">
                <?php foreach(['fr' => 'FR', 'fr-ca' => 'FR-CA', 'en' => 'EN', 'en-ca' => 'EN-CA'] as $code => $label): ?>
                    <a href="/<?= $code ?><?= $currentPath ?>"
                       class="lang-btn <?= $lang === $code ? 'active' : '' ?>"
                       aria-pressed="<?= $lang === $code ? 'true' : 'false' ?>">
                       <?= $label ?>
                    </a>
                <?php endforeach; ?> 
            </div>

            <!-- Panier -->
            <a href="/<?= $lang ?>/panier"
               class="btn-cart"
               aria-label="<?= $translator->safe('nav.cart') ?> (<?= $cartCount ?>)">
               <i class="ti ti-shopping-cart" aria-hidden="true"></i>
                <?php if ($cartCount > 0): ?>
                    <span class="cart-badge" aria-hidden="true"><?= $cartCount ?></span>
                <?php endif; ?>
            </a>
            
            <!-- Auth -->
            <?php if ($auth->guest()): ?>
                <a href="/<?= $lang ?>/connexion" class="btn-login">
                    <i class="ti ti-user" aria-hidden="true"></i>
                    <?= $translator->safe('nav.login') ?>
                </a>
            <?php else: ?>
                <?php require __DIR__ . '/PARTIALS/user-dropdown.php'; ?>
            <?php endif; ?>
        </div>
    </div>
</header>

<script src="<?= APP_URL_ASSETS ?>/JS/header.js"></script>