<?php
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['addcart'])) {

    $userId   = (int)$_SESSION['user_id'];
    $mapId    = (int)$_POST['map_id'];

    $stmt = $pdo->prepare("
        SELECT 1
        FROM commandesdetails cd
        INNER JOIN commandes c ON cd.IDCommande = c.ID_Commande
        WHERE c.ID_Users = ? AND cd.IDMap = ?
        LIMIT 1
    ");
    $stmt->execute([$userId, $mapId]);
    $carteExiste = $stmt->fetchColumn();

    if ($carteExiste) {
        $_SESSION['message_panier'] = "Cette carte est déjà associée à votre compte";
        header('Location: cart.php');
        exit;
    }

    /* 1. Récupérer ou créer la commande (panier) */
    $stmt = $pdo->prepare("
        SELECT ID_Commande 
        FROM commandes
        WHERE ID_Users = ? AND CommandeStatus = 1
    ");
    $stmt->execute([$userId]);
    $commandeId = $stmt->fetchColumn();

    if (!$commandeId) {
        $stmt = $pdo->prepare("
            INSERT INTO commandes (ID_Users, Prix_Total, CommandeStatus, DateCreation)
            VALUES (?, 0, 1, NOW())
        ");
        $stmt->execute([$userId]);
        $commandeId = $pdo->lastInsertId();
    }

    /* 2. Vérifier si la carte est déjà dans le panier */
    $stmt = $pdo->prepare("
        SELECT *
        FROM commandesdetails
        WHERE IDCommande = ? AND IDMap = ?
    ");
    $stmt->execute([$commandeId, $mapId]);
    $ligne = $stmt->fetch();

    if ($ligne) {
        $_SESSION['message_panier'] = "Carte déja présente dans le panier.";
        header('Location: cart.php');
        exit;
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO commandesdetails (IDCommande, IDMap)
            VALUES (?, ?)
        ");
        $stmt->execute([$commandeId, $mapId]);
    }

    /* 3. Recalcul du total */
    $stmt = $pdo->prepare("
        UPDATE commandes
        SET Prix_Total = (
            SELECT SUM(m.Prix)
            FROM commandesdetails
            INNER JOIN statesmap m ON m.ID_Map = commandesdetails.IDMap
            WHERE commandesdetails.IDCommande = ?
        )
        WHERE ID_Commande = ?
    ");
    $stmt->execute([$commandeId, $commandeId]);

    $_SESSION['message_panier'] = "Carte ajoutée au panier.";
    header('Location: cart.php');
    exit;
}
?>