<?php
//Permet d'afficher les traductions
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';
require_once 'INCLUDES/currency.php';

$sqlMap = "SELECT S.ID_Map, S.Map_Type, M.Libelle_TypeFR, M.Libelle_TypeEN, S.StateMap, S.Map_NameFR, S.Map_NameEN, S.Approx_Localisation, L.LibelleLocalisationFR, L.LibelleLocalisationEN, S.Prix
        FROM statesmap S
        INNER JOIN maptypes M ON M.Id_TypeMap = S.Map_Type
        INNER JOIN localisation L ON L.ID_Localisation = S.Approx_Localisation;";
$stmtMap = $pdo->prepare($sqlMap);
$stmtMap->execute();

$sqlLoc = "SELECT L.ID_Localisation, L.LibelleLocalisationFR, L.LibelleLocalisationEN
        FROM localisation L;";
$stmtLoc = $pdo->prepare($sqlLoc);
$stmtLoc->execute();
$localisations = $stmtLoc->fetchAll(PDO::FETCH_ASSOC);

$sqlType = "SELECT T.ID_TypeMap, T.Libelle_TypeFR, T.Libelle_TypeEN
            FROM maptypes T;";
$stmtType = $pdo->prepare($sqlType);
$stmtType->execute();
$types = $stmtType->fetchAll(PDO::FETCH_ASSOC);

$sqlPrice = "SELECT MAX(S.Prix) AS 'PrixMax'
             FROM statesmap S;";
$stmtPrice = $pdo->prepare($sqlPrice);
$stmtPrice->execute();
$maxPrice = $stmtPrice->fetch(PDO::FETCH_ASSOC);
$prixMax = (float)$maxPrice['PrixMax'];

$currency = $translations['currency-code'];
$locale   = $translations['currency_locale'];

$prixMaxConverted = Currency::convert($prixMax, $currency);
$prixMaxFormatted = Currency::format($prixMaxConverted, $currency, $locale);

?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $translations['header-home'] ?? 'Accueil' ?> - AMAZING-USA-CANADA.com</title>
    <!-- Liens CSS -->
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/mapslist.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <!-- FAVICON -->
    <link rel="icon" href="INCLUDES/ICONS/favicon.ico" type="image/x-icon">
</head>

<body>
    <?php include_once("INCLUDES/header.php"); ?>
    <main>
        <div class="presentation-text">
            <h2><?= htmlspecialchars($translations['maplist-presentationtext-h2']) ?></h2>
            <p><?= htmlspecialchars($translations['maplist-presentationtext-p']) ?></p>
        </div>

        <!-- Zone de filtre -->
        <div class="filters">
            <select id="filter-type">
                <option value=""><?= htmlspecialchars($translations['maplist-alltypes']) ?></option>
                <?php foreach ($types as $type): ?>
                    <option value="<?= htmlspecialchars($type['ID_TypeMap']) ?>"><?= htmlspecialchars($type["Libelle_Type$langBDD"]) ?></option>
                <?php endforeach; ?>
            </select>

            <select id="filter-location">
                <option value=""><?= htmlspecialchars($translations['maplist-alllocations']) ?></option>
                <?php foreach ($localisations as $loc): ?>
                    <option value="<?= htmlspecialchars($loc['ID_Localisation']) ?>"><?= htmlspecialchars($loc["LibelleLocalisation$langBDD"]) ?></option>
                <?php endforeach; ?>
            </select>

            <div class="price-filter">
                <label for="price-range"><?= htmlspecialchars($translations['maplist-price']) ?></label>
                <input type="range" id="price-min" min="0" max="<?= $prixMax ?>" value="0" step="0.01">
                <input type="range" id="price-max" min="0" max="<?= $prixMax ?>" value="<?= $prixMax ?>" step="0.01">
                <span id="price-display"
                    data-currency="<?= htmlspecialchars($currency) ?>"
                    data-rate="<?= Currency::getRate($currency) ?>"
                    data-max="<?= $prixMax ?>">
                    0 – <?= $prixMaxFormatted ?>
                </span>

            </div>
        </div>
        <div class="card-container">
            <?php while ($map = $stmtMap->fetch(PDO::FETCH_ASSOC)): ?>
                <?php
                $totalUnitaire = 0;
                //Conversion de Blob à base64
                $imageBase64 = base64_encode($map['StateMap']);
                $imageSrc = 'data:image/jpeg;base64,' . $imageBase64;

                $priceEuro = (float) $map['Prix'];
                $currency  = $translations['currency-code'];
                $locale    = $translations['currency_locale'];

                $convertedPrice = Currency::convert($priceEuro, $currency);
                $formattedPrice = Currency::format($convertedPrice, $currency, $locale);

                $packMaps = [];

                if ((int)$map['Map_Type'] === 3) {
                    $stmt = $pdo->prepare('
                                                SELECT 
                                                    S.ID_Map,
                                                    S.StateMap,
                                                    S.Map_NameFR,
                                                    S.Map_NameEN,
                                                    M.Libelle_TypeFR,
                                                    M.Libelle_TypeEN,
                                                    L.LibelleLocalisationFR,
                                                    L.LibelleLocalisationEN,
                                                    S.Prix
                                                FROM packsmap PM
                                                INNER JOIN statesmap S ON S.ID_Map = PM.IDMap
                                                INNER JOIN maptypes M ON M.Id_TypeMap = S.Map_Type
                                                INNER JOIN localisation L ON L.ID_Localisation = S.Approx_Localisation
                                                WHERE PM.IDPackMap = :id
                            ');
                    $stmt->execute(['id' => $map['ID_Map']]);
                    $packMaps = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($packMaps as $card) {
                        $totalUnitaire += (float)$card['Prix'];
                    }
                }
                ?>
                <div class="mapcard"
                    data-type="<?= (int)$map['Map_Type'] ?>"
                    data-location="<?= (int)$map['Approx_Localisation'] ?>"
                    data-price="<?= (float)$map['Prix'] ?>">
                    <img src="<?= $imageSrc ?>" alt=<?= htmlspecialchars($map["Map_Name$langBDD"]) ?> data-modal-image>
                    <h3><?= htmlspecialchars($map["Map_Name$langBDD"]) ?></h3>
                    <p><strong><?= $translations['home-mapshowcase-card-type'] ?></strong><?= htmlspecialchars($map["Libelle_Type$langBDD"]) ?></p>
                    <p><strong><?= $translations['home-mapshowcase-card-localisation'] ?></strong><?= htmlspecialchars($map["LibelleLocalisation$langBDD"]) ?></p>
                    <p><strong><?= $translations['home-mapshowcase-card-price'] ?></strong><strong><?= $formattedPrice ?></strong>
                        <?php if ($totalUnitaire != 0): ?>
                            <span style="text-decoration: line-through; color: red;">
                                <?= number_format($totalUnitaire, 2, ',', ' ') ?> €
                            </span>
                        <?php endif; ?>
                    </p>
                    <div class="mapcard-actions">
                        <form action="addcart.php" method="POST">
                            <input type="hidden" name="map_id" value="<?= htmlspecialchars($map['ID_Map']) ?>">
                            <button type="submit" name="addcart" class="btn-map"><?= $translations['home-mapshowcase-card-cart'] ?></button>
                        </form>
                        <a href="mapsdetails.php?id=<?= htmlspecialchars($map['ID_Map']) ?>" class="btn-map">
                            <?= $translations['home-mapshowcase-card-info'] ?>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div id="image-modal" class="modal">
            <span class="close">&times;</span>
            <img class="modal-content" id="modal-img">
        </div>
    </main>
    <?php include_once('INCLUDES/footer.php'); ?>
</body>
<script src="JAVASCRIPT/mapPrice.js"></script>
<script src="JAVASCRIPT/imageModal.js"></script>

</html>