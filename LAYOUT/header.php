<?php
require_once __DIR__ . '/../../GLOBAL-INCLUDES/CONFIGS/bootstrap.php';

$countries = cache_get("countries_$langID");
$locations = cache_get("locations_$langID");
try {
    $db = getDB();
    if ($countries == false) {
        $CSQL = "SELECT c.ISOCode, COALESCE(ct.Name, cf.Name) AS Name
                 FROM countries c
                 LEFT JOIN country_translations ct
                    ON ct.CountryID = c.CountryID
                    AND ct.Lang = :langID
                 LEFT JOIN country_translations cf
                    ON cf.CountryID = c.CountryID
                    AND cf.Lang = :fallbackID";
        
        $stmt = $db->prepare($CSQL);
        $stmt->execute([
            ':langID' => $langID,
            ':fallbackID' => $currentFallback
        ]);
        $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

        cache_set("countries_$langID", $countries, 3600);
    }
    if ($locations == false) {
        $LSQL = "SELECT l.LocationID, COALESCE(lt.Label, lf.Label) AS Label
                 FROM locations l
                 LEFT JOIN location_translations lt
                    ON lt.LocationID = l.LocationID
                    AND lt.Lang = :langID
                 LEFT JOIN location_translations lf
                    ON lf.LocationID = l.LocationID
                    AND lf.Lang = :fallbackID";

        $stmt = $db->prepare($LSQL);
        $stmt->execute([
            ':langID' => $langID,
            ':fallbackID' => $currentFallback
        ]);
        $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        cache_set("locations_$langID", $locations, 3600);
    }
} catch (Exception $e) {

}

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
                <a href="#" class="<?= $currentPage == 'index.php' ? 'active' : '' ?>">Accueil</a>
            </div>
            <div class="nav-item mega-parent">
                <a href="#" class="">Cartes Disponibles</a>
                <div class="mega-menu">
                    <div class="mega-section">
                        <h4>Pays</h4>
                        <?php foreach ($countries as $country): ?>
                            <a href="#">
                                <?= htmlspecialchars($country['Name'] ?? '') ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <div class="mega-section">
                        <h4>Type</h4>
                        <a href="#">Standard</a>
                        <a href="#">Gratuite</a>
                        <a href="#">Pack de Cartes</a>
                    </div>
                    <div class="mega-section">
                        <h4>Localisation</h4>
                        <?php foreach ($locations as $location): ?>
                            <a href="#">
                                <?= htmlspecialchars($location['Label'] ?? '') ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="nav-item">
                <a href="#">Galerie Photo</a>
            </div>
            <div class="nav-item">
                <a href="#">Forum</a>
            </div>
            <div class="nav-item">
                <a href="#">Contact</a>
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
            <a href="#" class="btn-login">Connexion</a>
            <a href="#" class="btn-primary">S'inscrire</a>
        </div>

    </div>
</header>