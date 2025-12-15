<?php
//Permet d'afficher les traductions
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';

$sqlMap = "SELECT S.ID_Map, S.Map_Type, M.Libelle_TypeFR, M.Libelle_TypeEN, S.StateMap, S.Map_NameFR, S.Map_NameEN, S.Approx_Localisation, L.LibelleLocalisationFR, L.LibelleLocalisationEN, S.Prix
        FROM Statesmap S
        INNER JOIN MapTypes M ON M.Id_TypeMap = S.Map_Type
        INNER JOIN Localisation L ON L.ID_Localisation = S.Approx_Localisation;";
$stmtMap = $pdo->prepare($sqlMap);
$stmtMap->execute();

$sqlLoc = "SELECT L.ID_Localisation, L.LibelleLocalisationFR, L.LibelleLocalisationEN
        FROM Localisation L;";
$stmtLoc = $pdo->prepare($sqlLoc);
$stmtLoc->execute();
$localisations = $stmtLoc->fetchAll(PDO::FETCH_ASSOC);

$sqlType = "SELECT T.ID_TypeMap, T.Libelle_TypeFR, T.Libelle_TypeEN
            FROM MapTypes T;";
$stmtType = $pdo->prepare($sqlType);
$stmtType->execute();
$types = $stmtType->fetchAll(PDO::FETCH_ASSOC);

$sqlPrice = "SELECT MAX(S.Prix) AS 'PrixMax'
             FROM Statesmap S;";
$stmtPrice = $pdo->prepare($sqlPrice);
$stmtPrice->execute();
$maxPrice = $stmtPrice->fetch(PDO::FETCH_ASSOC);
$prixMax = (float)$maxPrice['PrixMax'];

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
            <h2>Nos cartes disponibles</h2>
            <p>Découvrez toutes nos cartes des États-Unis et du Canada. Filtrez par type, prix ou localisation pour trouver celle qui vous convient.</p>
        </div>

        <!-- Zone de filtre -->
        <div class="filters">
        <select id="filter-type">
            <option value="">Tous types</option>
            <?php foreach ($types as $type): ?>
                <option value="<?= htmlspecialchars($type['ID_TypeMap']) ?>"><?= htmlspecialchars($type["Libelle_Type$langBDD"]) ?></option>
            <?php endforeach; ?>
            </select>

            <select id="filter-location">
                <option value="">Toutes localisations</option>
                <?php foreach ($localisations as $loc): ?>
                    <option value="<?= htmlspecialchars($loc['ID_Localisation']) ?>"><?= htmlspecialchars($loc["LibelleLocalisation$langBDD"]) ?></option>
                <?php endforeach; ?>
            </select>

            <div class="price-filter">
                <label for="price-range">Prix : </label>
                <input type="range" id="price-min" min="0" max="<?=$prixMax?>" value="0" step="0.01">
                <input type="range" id="price-max" min="0" max="<?=$prixMax?>" value="<?=$prixMax?>" step="0.01">
                <span id="price-display" data-currency="<?= htmlspecialchars($translations['filter-money']) ?>">0<?=$translations['filter-money']?> - <?=$prixMax?><?=$translations['filter-money']?><?=$translations['filter-money']?></span>
            </div>
        </div>
        <div class="card-container">
            <?php while ($map = $stmtMap->fetch(PDO::FETCH_ASSOC)): ?>
                <?php
                //Conversion de Blob à base64
                $imageBase64 = base64_encode($map['StateMap']);
                $imageSrc = 'data:image/jpeg;base64,' . $imageBase64;
                ?>
                <div class="mapcard"
                    data-type="<?=(int)$map['Map_Type']?>"
                    data-location="<?=(int)$map['Approx_Localisation']?>"
                    data-price="<?=(float)$map['Prix']?>">
                    <img src="<?= $imageSrc ?>" alt=<?= htmlspecialchars($map["Map_Name$langBDD"]) ?>>
                    <h3><?= htmlspecialchars($map["Map_Name$langBDD"]) ?></h3>
                    <p><strong><?= $translations['home-mapshowcase-card-type'] ?></strong><?= htmlspecialchars($map["Libelle_Type$langBDD"]) ?></p>
                    <p><strong><?= $translations['home-mapshowcase-card-localisation'] ?></strong><?= htmlspecialchars($map["LibelleLocalisation$langBDD"]) ?></p>
                    <p><strong><?= $translations['home-mapshowcase-card-price'] ?></strong><?= htmlspecialchars($map['Prix']) ?><?= $translations['home-mapshowcase-card-money'] ?></p>
                    <div class="mapcard-actions">
                        <form action="cart.php" method="POST">
                            <input type="hidden" name="map_id" value="<?= htmlspecialchars($map['ID_Map']) ?>">
                            <button type="submit" class="btn-map"><?= $translations['home-mapshowcase-card-cart'] ?></button>
                        </form>
                        <a href="details.php?id=<?= htmlspecialchars($map['ID_Map']) ?>" class="btn-map">
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
    <script src="JAVASCRIPT/map-filtering.js"></script>
    <script src="JAVASCRIPT/price-display.js"></script>
    <script src="JAVASCRIPT/imageModal.js"></script>
</html>