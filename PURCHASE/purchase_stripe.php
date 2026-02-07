<?php
require '../INCLUDES/LIBRAIRIES/Composer/vendor/autoload.php';
include_once("../INCLUDES/init.php");
require_once '../INCLUDES/config.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['commande_id'])) {
    header('Location: panier.php');
    exit;
}

\Stripe\Stripe::setApiKey('sk_test_51SyHnnC1EhL6zEwDHnEJ6gePSSmhT4IeFmmYQf23zEfdgs21RQX2CZ3U1ypjXzftpOWyppJy0kNcYpT3y7wbxK3Z00KPmfM13H');

$userId = (int)$_SESSION['user_id'];
$commandeId = (int)$_POST['commande_id'];

/* Récupérer le total */
$stmt = $pdo->prepare("SELECT Prix_Total FROM commandes WHERE ID_Commande = ? AND ID_Users = ?");
$stmt->execute([$commandeId, $userId]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande) { header('Location: panier.php'); exit; }

/* Créer session Stripe */
$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'currency' => 'eur',
            'product_data' => ['name' => 'Commande #' . $commandeId],
            'unit_amount' => $commande['Prix_Total'] * 100,
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => 'http://127.0.0.1/AMAZINGUSA/PURCHASE/SUCCESS/success_stripe.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => 'http://127.0.0.1/AMAZINGUSA/paiement.php',
]);
header("Location: " . $session->url);
exit;