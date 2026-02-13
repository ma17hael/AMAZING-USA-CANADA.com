<?php
//Permet d'afficher les traductions
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';
require_once 'INCLUDES/currency.php';
require 'INCLUDES/LIBRAIRIES/PHPMailer-7.0.2/src/SMTP.php';
require 'INCLUDES/LIBRAIRIES/PHPMailer-7.0.2/src/PHPMailer.php';
require 'INCLUDES/LIBRAIRIES/PHPMailer-7.0.2/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$limit = 7;
$sql = "SELECT S.ID_Map, S.Map_Type, M.Libelle_TypeFR, M.Libelle_TypeEN, S.StateMap, S.Map_NameFR, S.Map_NameEN, L.LibelleLocalisationFR, L.LibelleLocalisationEN, S.Prix
        FROM statesmap S
        INNER JOIN maptypes M ON M.Id_TypeMap = S.Map_Type
        INNER JOIN localisation L ON L.ID_Localisation = S.Approx_Localisation
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
            <div class="hero-content">
                <h1><?= $translations['home-hero-title'] ?></h1>
                <p><?= $translations['home-hero-paragraph'] ?></p>
                <a href="mapslist.php" class="btn-primary"><?= $translations['header-maplist'] ?? 'Liste des cartes' ?></a>
            </div>
        </section>
        <!-- Présentation -->
        <section class="acknowledgements">
            <div class="ack-container">
                <h2>Remerciements</h2>
                <p>
                    Nous tenons à remercier Philippe Schuler pour ses précieux conseils lors de la réalisation
                    de ce site Internet.
                </p>
            </div>
        </section>
        <section class="legal-notice">
            <div class="legal-container">
                <h2>Mentions importantes</h2>
                <p>
                    AMAZING-USA-CANADA.com n’est pas responsable si les informations disponibles sur ce site
                    s’avéraient parfois inexactes, incomplètes ou non à jour. Le contenu de ce site est fourni à
                    titre d'information générale uniquement et ne doit pas être utilisé comme unique source pour
                    prendre des décisions de randonnée sans consulter d’autres sources d’information.
                </p>
                <p>
                    Toute utilisation des informations contenues sur ce site se fera en toute connaissance de
                    cause et de votre propre chef. Les coordonnées GPS des sites présentés ont été sélectionnées
                    et renseignées d’après les informations disponibles sur d’autres sites web.
                    AMAZING-USA-CANADA.com décline toute responsabilité si ces informations s’avéraient inexactes.
                </p>
            </div>
        </section>
        <section class="presentation">
            <div class="presentation-container">
                <div class="presentation-text">
                    <h2><?= $translations['home-presentation-title'] ?></h2>
                    <p>
                        <?= $translations['home-presentation-paragraph'] ?>
                    </p>
                    <div class="card-container">
                        <div class="card">
                            <img src="INCLUDES/ICONS/map.svg" alt=<?= $translations['home-presentation-mapcard'] ?>>
                            <h3><?= $translations['home-presentation-mapcard-title'] ?></h3>
                            <p><?= $translations['home-presentation-mapcard-paragraph'] ?></p>
                        </div>
                        <div class="card">
                            <img src="INCLUDES/ICONS/lock.svg" alt=<?= $translations['home-presentation-accesscard'] ?>>
                            <h3><?= $translations['home-presentation-accesscard-title'] ?></h3>
                            <p><?= $translations['home-presentation-accesscard-paragraph'] ?></p>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <!-- Quelques Cartes de la Base de données -->
        <section class="mapShowcase">
            <h2><?= $translations['home-mapshowcase-title'] ?></h2>
            <div class="carrousel-container">
                <button class="prev">&#10094;</button>
                <div class="carrousel-track">
                    <?php foreach ($showcaseMap as $map): ?>
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
                        <div class="mapcard">
                            <img src="<?= $imageSrc ?>" alt=<?= htmlspecialchars($map["Map_Name$langBDD"]) ?> data-modal-image>
                            <h3><?= htmlspecialchars($map["Map_Name$langBDD"]) ?></h3>
                            <p><strong><?= $translations['home-mapshowcase-card-type'] ?></strong><?= htmlspecialchars($map["Libelle_Type$langBDD"]) ?></p>
                            <p><strong><?= $translations['home-mapshowcase-card-localisation'] ?></strong><?= htmlspecialchars($map["LibelleLocalisation$langBDD"]) ?>
                            <p><strong><?= $translations['home-mapshowcase-card-price'] ?></strong><?= $formattedPrice ?>
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
                    <?php endforeach; ?>
                </div>
                <button class="next">&#10095;</button>
            </div>
            <div id="image-modal" class="modal">
                <span class="close">&times;</span>
                <img class="modal-content" id="modal-img">
            </div>
        </section>
        <section class="contact-form-section">
            <div class="contact-form-container">
                <h2><?= $translations['contact-title'] ?></h2>
                <?php
                if (isset($_POST['contact_submit'])) {
                    $name = htmlspecialchars(trim($_POST['contact_name']));
                    $email = htmlspecialchars(trim($_POST['contact_email']));
                    $message = htmlspecialchars(trim($_POST['contact_message']));
                    $errors = [];

                    if (empty($name)) $errors[] = $translations['contact-error-name'];
                    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = $translations['contact-error-email'];
                    if (empty($message)) $errors[] = $translations['contact-error-message'];

                    if (empty($errors)) {
                        $mail = new PHPMailer(true);
                        try {
                            // Configuration SMTP
                            $mail->isSMTP();
                            $mail->Host = SMTP_HOST;
                            $mail->SMTPAuth = true;
                            $mail->Username = SMTP_USER;
                            $mail->Password = SMTP_PASS;
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port = SMTP_PORT;

                            $mail->CharSet = 'UTF-8';
                            $mail->Encoding = 'base64';

                            // Destinataire et expéditeur
                            $mail->setFrom($email, $name);
                            $mail->addAddress(SMTP_USER); // email depuis la base
                            $mail->addReplyTo($email, $name);

                            // Contenu
                            $mail->isHTML(false);
                            $mail->Subject = 'Nouveau message depuis le formulaire de contact';
                            $mail->Body    = $message;

                            $mail->send();
                            echo '<p class="contact-success">' . htmlspecialchars($translations['contact-success']) . '</p>';
                        } catch (Exception $e) {
                            echo '<p class="contact-error">' . htmlspecialchars(sprintf($translations['contact-error-send'], $mail->ErrorInfo)) . '</p>';
                        }
                    } else {
                        foreach ($errors as $err) {
                            echo '<p class="contact-error">' . htmlspecialchars($err) . '</p>';
                        }
                    }
                }
                ?>
                <form method="POST" action="#contact-form">
                    <input type="text" name="contact_name" placeholder="<?= htmlspecialchars($translations['contact-name-placeholder']) ?>" required>
                    <input type="email" name="contact_email" placeholder="<?= htmlspecialchars($translations['contact-email-placeholder']) ?>" required>
                    <textarea name="contact_message" placeholder="<?= htmlspecialchars($translations['contact-message-placeholder']) ?>" required></textarea>
                    <button type="submit" name="contact_submit"><?= $translations['contact-submit'] ?></button>
                </form>
            </div>
        </section>
    </main>
    <?php include_once('INCLUDES/footer.php'); ?>
</body>
<script src="JAVASCRIPT/map-carrousel.js"></script>
<script src="JAVASCRIPT/imageModal.js"></script>

</html>