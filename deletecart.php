<?php
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $userId = (int)$_SESSION['user_id'];
    $mapId  = (int)$_GET['id'];

    /* 1. Récupérer la commande active (panier) */
    $stmt = $pdo->prepare("
        SELECT ID_Commande
        FROM commandes
        WHERE ID_Users = ? AND CommandeStatus = 1
        LIMIT 1
    ");
    $stmt->execute([$userId]);
    $commandeId = $stmt->fetchColumn();

    if ($commandeId) {
        /* 2. Vérifier que la carte est bien dans cette commande */
        $stmt = $pdo->prepare("
            SELECT 1
            FROM commandesdetails
            WHERE IDCommande = ? AND IDMap = ?
            LIMIT 1
        ");
        $stmt->execute([$commandeId, $mapId]);

        if ($stmt->fetchColumn()) {
            /* 3. Supprimer la carte */
            $stmt = $pdo->prepare("
                DELETE FROM commandesdetails
                WHERE IDCommande = ? AND IDMap = ?
            ");
            $stmt->execute([$commandeId, $mapId]);

            /* 4. Recalculer le total du panier */
            $stmt = $pdo->prepare("
                UPDATE commandes
                SET Prix_Total = (
                    SELECT COALESCE(SUM(m.Prix), 0)
                    FROM commandesdetails cd
                    INNER JOIN statesmap m ON m.ID_Map = cd.IDMap
                    WHERE cd.IDCommande = ?
                )
                WHERE ID_Commande = ?
            ");
            $stmt->execute([$commandeId, $commandeId]);

            $_SESSION['message_panier'] = "Carte retirée du panier.";
        }
    }
}

header('Location: cart.php');
exit;