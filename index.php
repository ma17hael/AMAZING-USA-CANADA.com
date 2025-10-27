<?php
//Permet d'afficher les traductions
include_once("INCLUDES/init.php");
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
        </main>    
    </body>
</html>