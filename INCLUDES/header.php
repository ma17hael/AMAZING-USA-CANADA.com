<?php
include_once("init.php");
?>
<script>
    const currentLangFromPHP = "<?= $lang ?>";
</script>
<header class="site-header">
  <div class="header-container">
    <!-- Section du logo -->
    <div class="logo-section">
      <div class="logo-wrapper">
        <div class="logo-3d">
            <img src="INCLUDES/IMAGES/AMERICALogo.webp" alt="<?= $translations['header-USLogo'] ?>" class="logo-face usa">
            <img src="INCLUDES/IMAGES/CANADALogo.webp" alt="<?= $translations['header-CALogo'] ?>" class="logo-face canada">
        </div>
      </div>
      <span class="site-name">AMAZING-USA-CANADA.com</span>
    </div>
    <!-- Section navigation -->
     <nav class="main-nav">
        <ul>
           <li><a href="index.php"><?= $translations['header-home'] ?></a></li>
           <li><a href="mapslist.php"><?= $translations['header-maplist'] ?></a></li>
           <li><a href="about.php"><?= $translations['header-about'] ?></a></li>  
        </ul>
     </nav>
    <!-- Section des actions utilisateurs (Panier/Compte/Langue) -->
    <div class="header-actions">
      <button class="icon-btn">
        <img src="INCLUDES/ICONS/cart.svg" alt=<?= $translations['header-cart'] ?>>
      </button>
      <button class="icon-btn">
        <img src="INCLUDES/ICONS/user.svg" alt=<?= $translations['header-account'] ?>>
      </button>
      <div class="language-selector">
        <button id="langBtn">
            <img src="INCLUDES/ICONS/FRflag.svg" alt=<?= $translations['header-currentLang'] ?> id="current-flag">
        </button>
        <ul id="langMenu" class="hidden">
            <li data-lang="fr"><img src="INCLUDES/ICONS/FRflag.svg" alt="<?= $translations['header-FRLang'] ?>"><?= $translations['header-FRLang'] ?></li>
            <li data-lang="us"><img src="INCLUDES/ICONS/USflag.svg" alt="<?= $translations['header-USLg'] ?>"><?= $translations['header-USLang'] ?></li>
            <li data-lang="ca"><img src="INCLUDES/ICONS/CAflag.svg" alt="<?= $translations['header-CALg'] ?>"><?= $translations['header-CALang'] ?></li>
        </ul>
      </div>
    </div>
  </div>
  <script src="JAVASCRIPT/logo-rotate.js"></script>
  <script src="JAVASCRIPT/lang-selector.js"></script>
</header>
