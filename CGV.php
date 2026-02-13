<?php
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?? 'fr' ?>">

<head>
    <meta charset="UTF-8">
    <title><?= $translations['cgv-title'] ?></title>
    <link rel="stylesheet" href="CSS/legalnotice.css">
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <!-- FAVICON -->
    <link rel="icon" href="INCLUDES/ICONS/favicon.ico" type="image/x-icon">
</head>
<body>
    <?php include_once("INCLUDES/header.php"); ?>
    <div class="legal-container">
        <h1><?= $translations['cgv-title'] ?></h1>
        <p>
            Les présentes Conditions Générales de Vente régissent les ventes effectuées sur le site <strong>Amazing-USA-Canada.com</strong>. En passant commande, l'utilisateur accepte sans réserve ces conditions.
        </p>
        <h2>1. Éditeur et identification</h2>
        <p>
            Nom du site : <strong>[Nom du site]</strong><br>
            Éditeur : <strong>[Nom et prénom ou raison sociale]</strong><br>
            Statut juridique : <strong>[Auto-entrepreneur / Société]</strong><br>
            Adresse : <strong>[Adresse complète]</strong><br>
            Email : <strong>[contact@domaine.fr]</strong><br>
            Téléphone : <strong>[Numéro de téléphone]</strong><br>
            Numéro SIRET : <strong>[XXXXXXXXXXXXX]</strong>
        </p>
        <h2>2. Produits</h2>
        <p>
            Les produits vendus sont des cartes numériques. Les caractéristiques essentielles et les prix sont indiqués sur le site. L'éditeur se réserve le droit de modifier les offres et les prix à tout moment, sans préavis.
        </p>
        <h2>3. Commande</h2>
        <p>
            L’utilisateur peut passer commande en suivant le processus de commande sur le site. La commande n’est considérée comme ferme qu’après validation du paiement.
        </p>
        <h2>4. Prix</h2>
        <p>
            Les prix sont indiqués en euros (€), toutes taxes comprises (TTC), sauf mention contraire. Les frais de livraison, lorsqu’ils existent, sont précisés avant la confirmation de la commande.
        </p>
        <h2>5. Paiement</h2>
        <p>
            Le paiement est exigible immédiatement à la commande et peut être effectué par les moyens proposés sur le site. L’utilisateur garantit qu’il dispose des autorisations nécessaires pour utiliser le mode de paiement choisi.
        </p>
        <h2>6. Livraison</h2>
        <p>
            - <strong>Cartes numériques :</strong> uniquement hebergé et accesible sur le site après validation du paiement.<br>
        </p>
        <h2>7. Droit de rétractation</h2>
        <p>
            Conformément à la législation française, l’utilisateur dispose d’un délai de 14 jours à compter de la réception du produit pour exercer son droit de rétractation, sauf pour les cartes numériques si le téléchargement a été commencé avec accord préalable.
        </p>
        <h2>8. Retour et remboursement</h2>
        <p>
            Pour les produits physiques retournés conformément au droit de rétractation, l’éditeur procède au remboursement du prix du produit, hors frais de livraison, dans un délai de 14 jours après réception du retour. Les frais de retour sont à la charge de l’utilisateur.
        </p>
        <h2>9. Responsabilité</h2>
        <p>
            L’éditeur n’est responsable que de la bonne exécution des obligations prévues aux présentes CGV. La responsabilité de l’éditeur ne peut être engagée en cas de force majeure, de problèmes liés au transport ou d’utilisation non conforme des produits.
        </p>
        <h2>10. Propriété intellectuelle</h2>
        <p>
            Tous les produits et contenus du site sont protégés par la législation sur la propriété intellectuelle. Toute reproduction ou exploitation non autorisée est interdite.
        </p>
        <h2>11. Données personnelles</h2>
        <p>
            Les données collectées lors de la commande sont utilisées pour le traitement des commandes et la gestion du compte client, conformément au Règlement Général sur la Protection des Données (RGPD). L’utilisateur dispose d’un droit d’accès, de rectification et de suppression de ses données.
        </p>
        <h2>12. Litiges</h2>
        <p>
            Les présentes CGV sont soumises au droit français. En cas de litige, les tribunaux français sont seuls compétents. Une solution amiable sera recherchée avant toute action judiciaire.
        </p>
        <h2>13. Acceptation</h2>
        <p>
            En validant sa commande, l’utilisateur reconnaît avoir pris connaissance et accepté les présentes Conditions Générales de Vente.
        </p>
    </div>
    <?php include_once('INCLUDES/footer.php'); ?> 
</body>
</html>