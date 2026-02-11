<?php
//Permet d'afficher les traductions
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';
require_once 'INCLUDES/currency.php';

$limit = 7;
$sql = "SELECT S.ID_Map, M.Libelle_TypeFR, M.Libelle_TypeEN, S.StateMap, S.Map_NameFR, S.Map_NameEN, L.LibelleLocalisationFR, L.LibelleLocalisationEN, S.Prix
        FROM Statesmap S
        INNER JOIN MapTypes M ON M.Id_TypeMap = S.Map_Type
        INNER JOIN Localisation L ON L.ID_Localisation = S.Approx_Localisation
        ORDER BY RAND()
        LIMIT :limite";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limite', $limit, PDO::PARAM_INT);
$stmt->execute();

$showcaseMap = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $translations['header-home'] ?? 'Accueil' ?> - AMAZING-USA-CANADA.com</title>
        <!-- Liens CSS -->
        <link rel="stylesheet" href="CSS/index.css">
        <link rel="stylesheet" href="CSS/header.css">
        <link rel="stylesheet" href="CSS/footer.css">
        <!-- FAVICON -->
        <link rel="icon" href="INCLUDES/ICONS/favicon.ico" type="image/x-icon">
    </head>
    <body>
        <?php include_once("INCLUDES/header.php"); ?>
        <main class="home-main">
            <!-- Bannière/Hero -->
            <section class="hero">
                <div class="hero-overlay"></div>
                <div class ="hero-content">
                    <h1><?= $translations['home-hero-title']?></h1>
                    <p><?= $translations['home-hero-paragraph']?></p>
                    <a href="mapslist.php" class="btn-primary"><?= $translations['header-maplist'] ?? 'Liste des cartes' ?></a>
                </div>
            </section>
            <!-- Présentation -->
            <section class="presentation">
                <div class="presentation-container">
                    <div class="presentation-text">
                        <h2><?= $translations['home-presentation-title']?></h2>
                        <p>
                            <?= $translations['home-presentation-paragraph']?>
                        </p>
                        <div class="card-container">
                            <div class="card">
                                <img src="INCLUDES/ICONS/map.svg" alt=<?= $translations['home-presentation-mapcard']?>>
                                <h3><?= $translations['home-presentation-mapcard-title']?></h3>
                                <p><?= $translations['home-presentation-mapcard-paragraph']?></p>
                            </div>
                            <div class="card">
                                <img src="INCLUDES/ICONS/lock.svg" alt=<?= $translations['home-presentation-accesscard']?>>
                                <h3><?= $translations['home-presentation-accesscard-title']?></h3>
                                <p><?= $translations['home-presentation-accesscard-paragraph']?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
            </section>
            <!-- Quelques Cartes de la Base de données -->
            <section class="mapShowcase">
                <h2><?= $translations['home-mapshowcase-title']?></h2>
                <div class="carrousel-container">
                    <button class="prev">&#10094;</button>
                    <div class="carrousel-track">
                        <?php foreach ($showcaseMap as $map): ?>
                            <?php
                                //Conversion de Blob à base64
                                $imageBase64 = base64_encode($map['StateMap']);
                                $imageSrc = 'data:image/jpeg;base64,' . $imageBase64;

                                $priceEuro = (float) $map['Prix'];
                                $currency  = $translations['currency-code'];
                                $locale    = $translations['currency_locale'];

                                $convertedPrice = Currency::convert($priceEuro, $currency);
                                $formattedPrice = Currency::format($convertedPrice, $currency, $locale);
                            ?>
                            <div class="mapcard">
                                <img src="<?=$imageSrc?>" alt=<?=htmlspecialchars($map["Map_Name$langBDD"])?> data-modal-image>
                                <h3><?=htmlspecialchars($map["Map_Name$langBDD"])?></h3>
                                <p><strong><?=$translations['home-mapshowcase-card-type']?></strong><?=htmlspecialchars($map["Libelle_Type$langBDD"])?></p>
                                <p><strong><?=$translations['home-mapshowcase-card-localisation']?></strong><?=htmlspecialchars($map["LibelleLocalisation$langBDD"])?>
                                <p><strong><?=$translations['home-mapshowcase-card-price']?></strong><?= $formattedPrice ?></p>
                                <div class="mapcard-actions">
                                    <form action="addcart.php" method="POST">
                                        <input type="hidden" name="map_id" value="<?= htmlspecialchars($map['ID_Map'])?>">
                                        <button type="submit" name="addcart" class="btn-map"><?=$translations['home-mapshowcase-card-cart']?></button>
                                    </form>
                                    <a href="mapsdetails.php?id=<?=htmlspecialchars($map['ID_Map'])?>" class="btn-map">
                                        <?=$translations['home-mapshowcase-card-info']?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach;?>
                    </div>
                    <button class="next">&#10095;</button>
                </div>
                <div id="image-modal" class="modal">
                    <span class="close">&times;</span>
                    <img class="modal-content" id="modal-img">
                </div>
            </section>
        </main>
        <?php include_once('INCLUDES/footer.php'); ?>    
    </body>
    <script src="JAVASCRIPT/map-carrousel.js"></script>
    <script src="JAVASCRIPT/imageModal.js"></script>
</html>