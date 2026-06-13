<?php

/** @var string $lang */
/** @var string $currentPath */

$isMapsActive = str_starts_with($currentPath, '/cartes');
// Directions avec leur icône et slug URL
$directions = [
    'nord'       => ['label' => $translator->safe('dir.north'),     'icon' => 'ti-arrow-up',        'class' => 'dir-n',  'short' => 'N'],
    'nord-est'   => ['label' => $translator->safe('dir.northeast'), 'icon' => 'ti-arrow-up-right',  'class' => 'dir-ne', 'short' => 'NE'],
    'est'        => ['label' => $translator->safe('dir.east'),      'icon' => 'ti-arrow-right',      'class' => 'dir-e',  'short' => 'E'],
    'sud-est'    => ['label' => $translator->safe('dir.southeast'), 'icon' => 'ti-arrow-down-right', 'class' => 'dir-se', 'short' => 'SE'],
    'sud'        => ['label' => $translator->safe('dir.south'),     'icon' => 'ti-arrow-down',       'class' => 'dir-s',  'short' => 'S'],
    'sud-ouest'  => ['label' => $translator->safe('dir.southwest'), 'icon' => 'ti-arrow-down-left',  'class' => 'dir-sw', 'short' => 'SO'],
    'ouest'      => ['label' => $translator->safe('dir.west'),      'icon' => 'ti-arrow-left',       'class' => 'dir-w',  'short' => 'O'],
    'nord-ouest' => ['label' => $translator->safe('dir.northwest'), 'icon' => 'ti-arrow-up-left',    'class' => 'dir-nw', 'short' => 'NO'],
    'centre'     => ['label' => $translator->safe('dir.center'),    'icon' => 'ti-circle-dot',       'class' => 'dir-c',  'short' => '·'],
];
// Types de cartes
$types = [
    'gratuite' => [
        'label' => $translator->safe('type.free'),
        'desc'  => $translator->safe('type.free_desc'),
        'icon'  => 'ti-gift',
        'class' => 'free',
    ],
    'classique' => [
        'label' => $translator->safe('type.classic'),
        'desc'  => $translator->safe('type.classic_desc'),
        'icon'  => 'ti-map-2',
        'class' => 'classic',
    ],
    'lot' => [
        'label' => $translator->safe('type.pack'),
        'desc'  => $translator->safe('type.pack_desc'),
        'icon'  => 'ti-stack-2',
        'class' => 'pack',
    ],
];
// Pays avec leur compteur (à brancher sur MapRepository plus tard)
$countries = [
    'usa'    => ['flag' => '🇺🇸', 'label' => $translator->safe('country.usa'),    'slug' => 'us', 'count' => 0],
    'canada' => ['flag' => '🇨🇦', 'label' => $translator->safe('country.canada'), 'slug' => 'ca', 'count' => 0],
];
?>
<div class="nav-mega-wrapper">
    <button class="nav-link js-mega-btn <?= $isMapsActive ? 'active' : '' ?>"
        aria-has-popup="true"
        aria-expanded="false"
        aria-controls="megaMenu">
        <i class="ti ti-map" aria-hidden="true"></i>
        <?= $translator->safe('nav.maps_available') ?>
        <i class="ti ti-chevron-down" aria-hidden="true"></i>
    </button>

    <div class="mega-menu" id="megaMenu" role="dialog"
        aria-label="<?= $translator->safe('mega.aria_label') ?>">
        <!-- En-tête -->
        <div class="mega-header">
            <span class="mega-header-title">
                <?= $translator->safe('mega.browse') ?>
            </span>
            <a href="/<?= $lang ?>/cartes" class="mega-header-link">
                <?= $translator->safe('mega.see_all') ?>
                <i class="ti ti-arrow-right" aria-hidden="true"></i>
            </a>
        </div>
        <!-- Corps -->
        <div class="mega-body">
            <!-- Section Pays -->
            <div class="mega-section">
                <div class="mega-section-title">
                    <i class="ti ti-world" aria-hidden="true"></i>
                    <?= $translator->safe('mega.country') ?>
                </div>
                <?php foreach ($countries as $country): ?>
                    <a href="/<?= $lang ?>/cartes?pays=<?= $country['slug'] ?>"
                        class="mega-country">
                        <span class="mega-country-flag"><?= $country['flag'] ?></span>
                        <div class="mega-country-info">
                            <div class="mega-country-name"><?= $country['label'] ?></div>
                            <div class="mega-country-count">
                                <?= $country['count'] ?> <?= $translator->safe('mega.maps_count') ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            <!-- Section Direction -->
            <div class="mega-section">
                <div class="mega-section-title">
                    <i class="ti ti-compass" aria-hidden="true"></i>
                    <?= $translator->safe('mega.location') ?>
                </div>
                <div class="compass-wrapper">
                    <!-- Lignes de la boussole -->
                    <svg class="compass-lines" viewBox="0 0 260 260"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <line x1="130" y1="40" x2="130" y2="220"
                            stroke="rgba(91,141,217,0.12)" stroke-width="1" stroke-dasharray="3,4" />
                        <line x1="40" y1="130" x2="220" y2="130"
                            stroke="rgba(91,141,217,0.12)" stroke-width="1" stroke-dasharray="3,4" />
                        <line x1="63" y1="63" x2="197" y2="197"
                            stroke="rgba(91,141,217,0.08)" stroke-width="1" stroke-dasharray="3,4" />
                        <line x1="197" y1="63" x2="63" y2="197"
                            stroke="rgba(91,141,217,0.08)" stroke-width="1" stroke-dasharray="3,4" />
                        <circle cx="130" cy="130" r="28"
                            stroke="rgba(91,141,217,0.12)" stroke-width="1" fill="none" />
                        <circle cx="130" cy="130" r="85"
                            stroke="rgba(91,141,217,0.06)" stroke-width="1" fill="none" />
                    </svg>
                    <?php foreach ($directions as $slug => $dir): ?>
                        <a href="/<?= $lang ?>/cartes?direction=<?= $slug ?>"
                            class="compass-item <?= $dir['class'] ?>"
                            title="<?= $dir['label'] ?>"
                            aria-label="<?= $dir['label'] ?>">
                            <div class="compass-btn <?= $slug === 'centre' ? 'compass-btn--center' : '' ?>">
                                <i class="ti <?= $dir['icon'] ?>" aria-hidden="true"></i>
                            </div>
                            <span class="compass-label"><?= $dir['short'] ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Section Type -->
            <div class="mega-section">
                <div class="mega-section-title">
                    <i class="ti ti-tag" aria-hidden="true"></i>
                    <?= $translator->safe('mega.type') ?>
                </div>
                <?php foreach ($types as $slug => $type): ?>
                    <a href="/<?= $lang ?>/cartes?type=<?= $slug ?>"
                        class="mega-type">
                        <div class="mega-type-icon <?= $type['class'] ?>">
                            <i class="ti <?= $type['icon'] ?>" aria-hidden="true"></i>
                        </div>
                        <div class="mega-type-info">
                            <div class="mega-type-label"><?= $type['label'] ?></div>
                            <div class="mega-type-desc"><?= $type['desc'] ?></div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Footer -->
        <div class="mega-footer">
            <a href="/<?= $lang ?>/cartes" class="mega-search">
                <i class="ti ti-search" aria-hidden="true"></i>
                <span><?= $translator->safe('mega.search_placeholder') ?></span>
            </a>
            <a href="/<?= $lang ?>/cartes" class="mega-see-all">
                <i class="ti ti-map" aria-hidden="true"></i>
                <?= $translator->safe('mega.all_catalog') ?>
            </a>
        </div>
    </div>
</div>