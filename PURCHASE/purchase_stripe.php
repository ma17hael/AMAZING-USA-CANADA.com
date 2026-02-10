<?php
require '../INCLUDES/LIBRAIRIES/Composer/vendor/autoload.php';
include_once("../INCLUDES/init.php");
require_once '../INCLUDES/config.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['commande_id'])) {
    header('Location: ../cart.php');
    exit;
}

$stripeSecretKey = getenv('STRIPE_SECRET_KEY');
if (!$stripeSecretKey) {
    $_SESSION['message_panier'] = $translations['payment-config-missing-stripe'];
    header('Location: ../purchasing.php');
    exit;
}

\Stripe\Stripe::setApiKey($stripeSecretKey);

$userId = (int)$_SESSION['user_id'];
$commandeId = (int)$_POST['commande_id'];

/* Récupérer le total */
$stmt = $pdo->prepare("SELECT Prix_Total FROM commandes WHERE ID_Commande = ? AND ID_Users = ?");
$stmt->execute([$commandeId, $userId]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande) {
    header('Location: ../cart.php');
    exit;
}

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
    'success_url' => 'http://127.0.0.1/AMAZING-USA-CANADA.com/PURCHASE/SUCESSS/sucess_stripe.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => 'http://127.0.0.1/AMAZING-USA-CANADA.com/purchasing.php',
    'metadata' => [
        'commande_id' => (string) $commandeId,
    ],
]);
header("Location: " . $session->url);
exit;
