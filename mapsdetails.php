<?php
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';

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

$stmt = $pdo->prepare('SELECT Flag
                       FROM StateFlag
                       WHERE Id_Map = :id');
$stmt->execute(['id' => $id]);
$flags = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$map) {
    http_response_code(404);
    exit('Carte inexistante');
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
            <h1><?=$translations['mapsdetails-h1-main-title']?></h1>
        </div>
        <section class="maps-essentials">
            <div class="map-static">
                <?php
                //Conversion de Blob à base64
                $imageBase64 = base64_encode($map['StateMap']);
                $imageSrc = 'data:image/jpeg;base64,' . $imageBase64;
                ?>
                <img src=<?= $imageSrc ?> alt=<?= htmlspecialchars($map["Map_Name$langBDD"]) ?> data-modal-image>
            </div>
            <div class="map-details">
                <h2><?= htmlspecialchars($map["Map_Name$langBDD"]) ?></h2>
                <div class="map-flags">
                    <?php foreach ($flags as $flag): ?>
                        <?php
                        $flagBase64 = base64_encode($flag['Flag']);
                        $flagSrc = 'data:image/png;base64,' . $flagBase64;
                        ?>
                        <img src="<?= $flagSrc ?>" alt="Drapeau" class="flag-img">
                    <?php endforeach; ?>
                </div>
                <p>
                    <strong><?=$translations['home-mapshowcase-card-localisation']?></strong>
                    <?= htmlspecialchars($map["LibelleLocalisation$langBDD"]) ?>
                </p>
                <p>
                    <strong><?=$translations['home-mapshowcase-card-type']?></strong>
                    <?= htmlspecialchars($map["Libelle_Type$langBDD"]) ?>
                </p>
                <p>
                    <strong><?=$translations['home-mapshowcase-card-price']?></strong>
                    <?= htmlspecialchars($map['Prix']) ?> €
                </p>
                <a href="#" class="btn-map">
                    <?=$translations['home-mapshowcase-card-cart']?>
                </a>
            </div>
        </section>
        <div class="details-main-title">
            <h1><?=$translations['mapsdetails-h1-complementary-title']?></h1>
        </div>
        <section class="maps-essentials">
            <div class="countrymap-static">
                <?php
                //Conversion de Blob à base64
                $imageBase64 = base64_encode($map['Country_Map']);
                $imageSrc = 'data:image/jpeg;base64,' . $imageBase64;
                ?>
                <img src=<?= $imageSrc ?> alt=<?= htmlspecialchars($map["Map_Name$langBDD"]) ?> data-modal-image>
            </div>
            <div class="map-details">
                <h2><?=$translations['mapsdetails-h2-complementary-smalltitle']?></h2>
                <p>
                    <?=$translations['mapsdetails-p-complementary']?>
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

</html>