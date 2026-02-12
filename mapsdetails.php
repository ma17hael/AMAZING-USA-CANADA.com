<?php
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';
require_once 'INCLUDES/currency.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(404);
    exit('Carte Introuvable');
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare('SELECT S.*, L.LibelleLocalisationFR, L.LibelleLocalisationEN, M.Libelle_TypeFR, M.Libelle_TypeEN
                       FROM StatesMap S
                       INNER JOIN MapTypes M ON M.Id_TypeMap = S.Map_Type
                       INNER JOIN Localisation L ON L.ID_Localisation = S.Approx_Localisation
                       WHERE ID_Map = :id');
$stmt->execute(['id' => $id]);
$map = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$map) {
    http_response_code(404);
    exit('Carte inexistante');
}

$flags = [];
$showFlags = false;

if ((int) $map['Map_Type'] == 3) {
    $stmt = $pdo->prepare('
        SELECT SF.Flag, SF.Libelle_FlagFR, SF.Libelle_FlagEN
        FROM PacksMap PM
        INNER JOIN StateFlag SF ON SF.Id_Map = PM.IDMap
        WHERE IDPackMap = :id');
    $stmt->execute(['id' => $id]);
    $flags = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($flags)) {
        $showFlags = true;
    } else {
        $stmt = $pdo->prepare('SELECT Flag, Libelle_FlagFR, Libelle_FlagEN
                       FROM StateFlag
                       WHERE Id_Map = :id');
        $stmt->execute(['id' => $id]);
        $flags = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($flags)) {
            $showFlags = true;
        }
    }
} else {
    $stmt = $pdo->prepare('SELECT Flag, Libelle_FlagFR, Libelle_FlagEN
                       FROM StateFlag
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
        FROM PacksMap PM
        INNER JOIN StatesMap S ON S.ID_Map = PM.IDMap
        INNER JOIN MapTypes M ON M.Id_TypeMap = S.Map_Type
        INNER JOIN Localisation L ON L.ID_Localisation = S.Approx_Localisation
        WHERE PM.IDPackMap = :id
    ');
    $stmt->execute(['id' => $id]);
    $packMaps = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                    <?= htmlspecialchars($map['Prix']) ?> €
                </p>
                <form action="addcart.php" method="POST">
                    <input type="hidden" name="map_id" value="<?= htmlspecialchars($map['ID_Map']) ?>">
                    <button type="submit" name="addcart" class="btn-map"><?= $translations['home-mapshowcase-card-cart'] ?></button>
                </form>
            </div>
        </section>
        <?php if ((int) $map['Map_Type'] == 3): ?>
            <div class="details-main-title">
                <h1>Les cartes dans ce pack</h1>
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
