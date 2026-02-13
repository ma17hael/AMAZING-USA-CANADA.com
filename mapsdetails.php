<?php
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';
require_once 'INCLUDES/currency.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(404);
    exit($translations['voirmap-map-not-found']);
}

$id = (int) $_GET['id'];
$totalUnitaire = 0;
$details = [];

$stmt = $pdo->prepare('SELECT S.*, L.LibelleLocalisationFR, L.LibelleLocalisationEN, M.Libelle_TypeFR, M.Libelle_TypeEN
                       FROM statesmap S
                       INNER JOIN maptypes M ON M.Id_TypeMap = S.Map_Type
                       INNER JOIN localisation L ON L.ID_Localisation = S.Approx_Localisation
                       WHERE ID_Map = :id');
$stmt->execute(['id' => $id]);
$map = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$map) {
    http_response_code(404);
    exit($translations['voirmap-map-not-found']);
}

$flags = [];
$showFlags = false;

if ((int) $map['Map_Type'] == 3) {
    $stmt = $pdo->prepare('
        SELECT SF.Flag, SF.Libelle_FlagFR, SF.Libelle_FlagEN
        FROM packsmap PM
        INNER JOIN stateflag SF ON SF.Id_Map = PM.IDMap
        WHERE IDPackMap = :id');
    $stmt->execute(['id' => $id]);
    $flags = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($flags)) {
        $showFlags = true;
    } else {
        $stmt = $pdo->prepare('SELECT Flag, Libelle_FlagFR, Libelle_FlagEN
                       FROM stateflag
                       WHERE Id_Map = :id');
        $stmt->execute(['id' => $id]);
        $flags = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($flags)) {
            $showFlags = true;
        }
    }
} else {
    $stmt = $pdo->prepare('SELECT Flag, Libelle_FlagFR, Libelle_FlagEN
                       FROM stateflag
                       WHERE Id_Map = :id');
    $stmt->execute(['id' => $id]);
    $flags = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($flags)) {
        $showFlags = true;
    }
}

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
    $stmt->execute(['id' => $id]);
    $packMaps = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($packMaps as $card) {
        $totalUnitaire += (float)$card['Prix'];
    }

    $stmt = $pdo->prepare("
    SELECT *
    FROM mapspack_data
    WHERE IDMapPack = :id
");
    $stmt->execute(['id' => $id]);

    $details = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($map["Map_Name$langBDD"]) ?> - AMAZING-USA-CANADA.com</title>
    <link rel="stylesheet" href="CSS/mapsdetails.css">
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="icon" href="INCLUDES/ICONS/favicon.ico" type="image/x-icon">
</head>

<body>
    <?php include_once("INCLUDES/header.php"); ?>
    <main>
        <div class="details-main-title">
            <h1><?= $translations['mapsdetails-h1-main-title'] ?></h1>
        </div>
        <section class="maps-essentials">
            <div class="map-static">
                <?php
                //Conversion de Blob à base64
                $imageBase64 = base64_encode($map['StateMap']);
                $imageSrc = 'data:image/jpeg;base64,' . $imageBase64;
                ?>
                <img src="<?= $imageSrc ?>" alt="<?= htmlspecialchars($map["Map_Name$langBDD"]) ?>" data-modal-image>
            </div>
            <div class="map-details">
                <h2><?= htmlspecialchars($map["Map_Name$langBDD"]) ?></h2>
                <?php if ($showFlags): ?>
                    <div class="map-flags">
                        <?php foreach ($flags as $flag): ?>
                            <?php
                            $flagBase64 = base64_encode($flag['Flag']);
                            $flagSrc = 'data:image/png;base64,' . $flagBase64;
                            ?>
                            <div class="flag-item">
                                <img src="<?= $flagSrc ?>" alt="Drapeau <?= htmlspecialchars($flag["Libelle_Flag$langBDD"]) ?>" class="flag-img">
                                <p class="flag-name"><?= htmlspecialchars($flag["Libelle_Flag$langBDD"]) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <p>
                    <strong><?= $translations['home-mapshowcase-card-localisation'] ?></strong>
                    <?= htmlspecialchars($map["LibelleLocalisation$langBDD"]) ?>
                </p>
                <p>
                    <strong><?= $translations['home-mapshowcase-card-type'] ?></strong>
                    <?= htmlspecialchars($map["Libelle_Type$langBDD"]) ?>
                </p>
                <p>
                    <strong><?= $translations['home-mapshowcase-card-price'] ?></strong>

                    <span style="margin-right: 8px;">
                        <?= number_format((float)$map['Prix'], 2, ',', ' ') ?> €
                    </span>
                    <?php if ($totalUnitaire != 0): ?>
                        <span style="text-decoration: line-through; color: red;">
                            <?= number_format($totalUnitaire, 2, ',', ' ') ?> €
                        </span>
                    <?php endif; ?>
                </p>
                <p>
                    <strong><?= $translations['mapsdetails-places-count'] ?></strong>
                    <?= htmlspecialchars($map['NbPlaces']) ?>
                </p>
                <form action="addcart.php" method="POST">
                    <input type="hidden" name="map_id" value="<?= htmlspecialchars($map['ID_Map']) ?>">
                    <button type="submit" name="addcart" class="btn-map"><?= $translations['home-mapshowcase-card-cart'] ?></button>
                </form>
            </div>
        </section>
        <?php if ((int) $map['Map_Type'] == 3): ?>
            <div class="details-main-title">
                <h1><?= $translations['mapsdetails-pack-maps-title'] ?></h1>
            </div>
            <?php if (!empty($packMaps)): ?>
                <section class="mapShowcase">
                    <div class="carrousel-container">
                        <button class="prev">&#10094;</button>

                        <div class="carrousel-track">
                            <?php foreach ($packMaps as $packMap): ?>
                                <?php
                                $imageBase64 = base64_encode($packMap['StateMap']);
                                $imageSrc = 'data:image/jpeg;base64,' . $imageBase64;

                                $priceEuro = (float) $packMap['Prix'];
                                $currency  = $translations['currency-code'];
                                $locale    = $translations['currency_locale'];

                                $convertedPrice = Currency::convert($priceEuro, $currency);
                                $formattedPrice = Currency::format($convertedPrice, $currency, $locale);
                                ?>
                                <div class="mapcard">
                                    <img src="<?= $imageSrc ?>"
                                        alt="<?= htmlspecialchars($packMap["Map_Name$langBDD"]) ?>"
                                        data-modal-image>

                                    <h3><?= htmlspecialchars($packMap["Map_Name$langBDD"]) ?></h3>

                                    <p>
                                        <strong><?= $translations['home-mapshowcase-card-type'] ?></strong>
                                        <?= htmlspecialchars($packMap["Libelle_Type$langBDD"]) ?>
                                    </p>

                                    <p>
                                        <strong><?= $translations['home-mapshowcase-card-localisation'] ?></strong>
                                        <?= htmlspecialchars($packMap["LibelleLocalisation$langBDD"]) ?>
                                    </p>

                                    <p>
                                        <strong><?= $translations['home-mapshowcase-card-price'] ?></strong>
                                        <?= $formattedPrice ?>
                                    </p>

                                    <div class="mapcard-actions">
                                        <a href="mapsdetails.php?id=<?= (int)$packMap['ID_Map'] ?>" class="btn-map">
                                            <?= $translations['home-mapshowcase-card-info'] ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <button class="next">&#10095;</button>
                    </div>
                </section>
            <?php endif; ?>
        <?php endif; ?>
        <div class="details-main-title">
            <h1><?= $translations['mapsdetails-h1-complementary-title'] ?></h1>
        </div>
        <section class="maps-essentials">
            <div class="countrymap-static">
                <?php
                //Conversion de Blob à base64
                $imageBase64 = base64_encode($map['Country_Map']);
                $imageSrc = 'data:image/jpeg;base64,' . $imageBase64;
                ?>
                <img src="<?= $imageSrc ?>" alt="<?= htmlspecialchars($map["Map_Name$langBDD"]) ?>" data-modal-image>
            </div>
            <div class="map-details">
                <h2><?= $translations['mapsdetails-h2-complementary-smalltitle'] ?></h2>
                <p>
                    <?= $translations['mapsdetails-p-complementary'] ?>
                </p>
                <?php if ($details): ?>
                    <ul class="pack-features">

                        <?php if ($details['NbTrails'] > 0): ?>
                            <li><?= (int)$details['NbTrails'] ?> Trails</li>
                        <?php endif; ?>

                        <?php if ($details['NbFalls'] > 0): ?>
                            <li><?= (int)$details['NbFalls'] ?> Waterfalls</li>
                        <?php endif; ?>

                        <?php if ($details['NbRetroDiners'] > 0): ?>
                            <li><?= (int)$details['NbRetroDiners'] ?> Retro Diners</li>
                        <?php endif; ?>

                        <?php if ($details['NbLighhouses'] > 0): ?>
                            <li><?= (int)$details['NbLighhouses'] ?> Lighthouses</li>
                        <?php endif; ?>

                        <?php if ($details['NbBridges'] > 0): ?>
                            <li><?= (int)$details['NbBridges'] ?> Bridges</li>
                        <?php endif; ?>

                        <?php if ($details['NbArches'] > 0): ?>
                            <li><?= (int)$details['NbArches'] ?> Arches</li>
                        <?php endif; ?>

                        <?php if ($details['NbRoute66'] > 0): ?>
                            <li><?= (int)$details['NbRoute66'] ?> Route 66 Stops</li>
                        <?php endif; ?>

                        <?php if ($details['NbRetroGaz'] > 0): ?>
                            <li><?= (int)$details['NbRetroGaz'] ?> Retro Gas Stations</li>
                        <?php endif; ?>

                        <?php if ($details['NbScenicRoad'] > 0): ?>
                            <li><?= (int)$details['NbScenicRoad'] ?> Scenic Roads</li>
                        <?php endif; ?>

                        <?php if ($details['NbHistoricGaz'] > 0): ?>
                            <li><?= (int)$details['NbHistoricGaz'] ?> Historic Gas Stations</li>
                        <?php endif; ?>

                        <?php if ($details['NbAutheticDiner'] > 0): ?>
                            <li><?= (int)$details['NbAutheticDiner'] ?> Authentic Diners</li>
                        <?php endif; ?>

                        <?php if ($details['NbSlots'] > 0): ?>
                            <li><?= (int)$details['NbSlots'] ?> Slot Machines</li>
                        <?php endif; ?>

                        <?php if ($details['NbGhostTowns'] > 0): ?>
                            <li><?= (int)$details['NbGhostTowns'] ?> Ghost Towns</li>
                        <?php endif; ?>

                        <?php if ($details['NbPetroglyph'] > 0): ?>
                            <li><?= (int)$details['NbPetroglyph'] ?> Petroglyph Sites</li>
                        <?php endif; ?>

                    </ul>
                <?php endif; ?>
            </div>
        </section>
        <div id="image-modal" class="modal">
            <span class="close">&times;</span>
            <img class="modal-content" id="modal-img">
        </div>
    </main>
    <?php include_once('INCLUDES/footer.php'); ?>
</body>
<script src="JAVASCRIPT/imageModal.js"></script>
<?php if ((int) $map['Map_Type'] === 3 && !empty($packMaps)): ?>
    <script src="JAVASCRIPT/map-carrousel.js"></script>
<?php endif; ?>

</html>