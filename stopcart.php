<?php
session_start();
require_once 'INCLUDES/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

/* Récupération de la commande active */
$stmt = $pdo->prepare("
    SELECT ID_Commande
    FROM commandes
    WHERE ID_Users = ? AND CommandeStatus = 1
");
$stmt->execute([$userId]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if ($commande) {
    $commandeId = $commande['ID_Commande'];

    /* Suppression des détails */
    $stmt = $pdo->prepare("
        DELETE FROM commandesdetails
        WHERE IDCommande = ?
    ");
    $stmt->execute([$commandeId]);

    /* Mise à jour du statut */
    $stmt = $pdo->prepare("
        UPDATE commandes
        SET CommandeStatus = 3
        WHERE ID_Commande = ?
    ");
    $stmt->execute([$commandeId]);
}

header('Location: cart.php');
exit;