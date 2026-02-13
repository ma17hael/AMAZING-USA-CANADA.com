<?php
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?? 'fr' ?>">

<head>
    <meta charset="UTF-8">
    <title><?= $translations['cgu-title'] ?></title>
    <link rel="stylesheet" href="CSS/legalnotice.css">
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <!-- FAVICON -->
    <link rel="icon" href="INCLUDES/ICONS/favicon.ico" type="image/x-icon">
</head>
<body>
    <?php include_once("INCLUDES/header.php"); ?>
    <div class="legal-container">
        <h1><?= $translations['cgu-title'] ?></h1>
        <p>
            Les présentes conditions d'utilisation régissent l'accès et l'utilisation du site <strong>[Nom du site]</strong>. En accédant à ce site, l'utilisateur accepte pleinement ces conditions.
        </p>
        <h2>1. Objet</h2>
        <p>
            Le site propose la vente et la consultation de cartes numériques et/ou physiques. Les présentes conditions définissent les droits et obligations de l'utilisateur et de l'éditeur du site.
        </p>
        <h2>2. Accès au site</h2>
        <p>
            L'accès au site est gratuit et accessible à tout utilisateur disposant d'un accès à Internet. L'éditeur se réserve le droit de suspendre, interrompre ou limiter l'accès au site, sans préavis ni indemnité.
        </p>
        <h2>3. Compte utilisateur</h2>
        <p>
            Certains services nécessitent la création d'un compte. L'utilisateur est responsable de la confidentialité de ses identifiants et de toutes les activités effectuées sous son compte. Toute utilisation frauduleuse devra être signalée à l'éditeur.
        </p>
        <h2>4. Propriété intellectuelle</h2>
        <p>
            L'ensemble des contenus présents sur le site, incluant textes, images, cartes, logos et données, est protégé par la législation sur la propriété intellectuelle. Toute reproduction, distribution ou exploitation sans autorisation est strictement interdite.
        </p>
        <h2>5. Responsabilité</h2>
        <p>
            L'éditeur met en œuvre tous les moyens raisonnables pour assurer un accès continu et sécurisé au site. Toutefois, il ne peut être tenu responsable des interruptions, erreurs, virus ou dommages résultant de l'utilisation du site.
        </p>
        <h2>6. Produits et services</h2>
        <p>
            Les informations sur les cartes et les prix sont fournies à titre indicatif. L'éditeur se réserve le droit de modifier à tout moment les caractéristiques ou les tarifs des produits. Les commandes sont soumises à disponibilité.
        </p>
        <h2>7. Paiement et sécurité</h2>
        <p>
            Les paiements sont effectués via les moyens proposés sur le site. L'utilisateur est responsable de la sécurité de ses informations de paiement et de l'exactitude de ses données lors de la transaction.
        </p>
        <h2>8. Données personnelles</h2>
        <p>
            Les données collectées sont traitées conformément à la <strong>politique de confidentialité</strong> et au Règlement Général sur la Protection des Données (RGPD). L'utilisateur dispose d'un droit d'accès, de rectification et de suppression de ses données personnelles.
        </p>
        <h2>9. Cookies</h2>
        <p>
            Le site peut utiliser des cookies pour améliorer l'expérience utilisateur et réaliser des statistiques. L'utilisateur peut configurer son navigateur pour les refuser.
        </p>
        <h2>10. Modification des conditions</h2>
        <p>
            L'éditeur se réserve le droit de modifier à tout moment les présentes conditions d'utilisation. Les modifications seront effectives dès leur publication sur le site. L'utilisateur est invité à les consulter régulièrement.
        </p>
        <h2>11. Droit applicable et juridiction</h2>
        <p>
            Les présentes conditions sont soumises au droit français. En cas de litige, les tribunaux français seront seuls compétents, sauf disposition légale contraire.
        </p>
    </div>
    <?php include_once('INCLUDES/footer.php'); ?> 
</body>
</html>