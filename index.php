<?php
//Permet d'afficher les traductions
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';

$limit = 7;
$sql = "SELECT S.ID_Map, M.Libelle_TypeFR, S.StateMap, S.Map_NameFR,L.LibelleLocalisationFR, S.Prix
        FROM Statesmap S
        INNER JOIN MapTypes M ON M.Id_TypeMap = S.Map_Type
        INNER JOIN Localisation L ON L.ID_Localisation = S.Approx_Localisation
        ORDER BY RAND()
        LIMIT :limite";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limite', $limit, PDO::PARAM_INT);
$stmt->execute();

$showcaseMap =$stmt->fetchAll(PDO::FETCH_ASSOC);
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
                                <img src="INCLUDES/ICONS/group-chat.svg" alt=<?= $translations['home-presentation-commentscard']?>>
                                <h3><?= $translations['home-presentation-commentscard-title']?></h3>
                                <p><?= $translations['home-presentation-commentscard-paragraph']?></p>
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
                <h2>Quelques Cartes à notre disposition</h2>
                <div class="carrousel-container">
                    <button class="prev">&#10094;</button>
                    <div class="carrousel-track">
                        <?php foreach ($showcaseMap as $map): ?>
                            <?php
                                //Conversion de Blob à base64
                                $imageBase64 = base64_encode($map['StateMap']);
                                $imageSrc = 'data:image/jpeg;base64,' . $imageBase64;
                            ?>
                            <div class="mapcard">
                                <img src="<?=$imageSrc?>" alt=<?=htmlspecialchars($map['Map_NameFR'])?>>
                                <h3><?=htmlspecialchars($map['Map_NameFR'])?></h3>
                                <p><?=htmlspecialchars($map['Libelle_TypeFR'])?></p>
                                <p><?=htmlspecialchars($map['LibelleLocalisationFR'])?>
                                <p><?=htmlspecialchars($map['Prix'])?></p>
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
    </body>
    <script src="JAVASCRIPT/map-carrousel.js"></script>
    <script src="JAVASCRIPT/imageModal.js"></script>
</html>