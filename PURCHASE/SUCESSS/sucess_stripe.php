<?php
require '../../INCLUDES/LIBRAIRIES/Composer/vendor/autoload.php';
include_once("../../INCLUDES/init.php");
require_once '../../INCLUDES/config.php';

if (!isset($_GET['session_id'])) {
    header('Location: ../../cart.php');
    exit;
}

$stripeSecretKey = getenv('STRIPE_SECRET_KEY');
if (!$stripeSecretKey) {
    $_SESSION['message_panier'] = $translations['payment-config-missing-stripe'];
    header('Location: ../../purchasing.php');
    exit;
}

\Stripe\Stripe::setApiKey($stripeSecretKey);
$session_id = $_GET['session_id'];

try {
    $session = \Stripe\Checkout\Session::retrieve($session_id);

    if ($session->payment_status === 'paid') {
        $commandeId = isset($session->metadata->commande_id) ? (int) $session->metadata->commande_id : 0;

        if ($commandeId <= 0) {
            throw new Exception($translations['payment-order-not-found']);
        }

        $stmt = $pdo->prepare("UPDATE commandes SET CommandeStatus = 2 WHERE ID_Commande = ? AND CommandeStatus = 1");
        $stmt->execute([$commandeId]);
    } else {
        throw new Exception($translations['payment-not-confirmed']);
    }

} catch (Exception $e) {
    $_SESSION['message_panier'] = "Erreur lors du paiement : " . $e->getMessage();
    header('Location: ../../cart.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<title><?= $translations['payment-stripe-success-title'] ?></title>
<link rel="stylesheet" href="../../CSS/header.css">
<link rel="stylesheet" href="../../CSS/footer.css">
<style>
body { font-family: 'Segoe UI', sans-serif; background:#f8fafc; margin:0; padding:0;}
.container { max-width:600px; margin:80px auto; padding:30px; background:#fff; border-radius:20px; box-shadow:0 4px 6px rgba(0,0,0,0.1); text-align:center;}
h1 { color:#2563eb; margin-bottom:20px;}
p { font-size:1.1rem; color:#1e293b;}
a.btn { display:inline-block; margin-top:20px; padding:12px 24px; border-radius:12px; text-decoration:none; background:linear-gradient(135deg,#2563eb,#1e40af); color:#fff; font-weight:600; }
a.btn:hover { background:linear-gradient(135deg,#1e40af,#2563eb); }
</style>
</head>
<body>
<?php include_once("../../INCLUDES/header.php"); ?>
<div class="container">
    <h1><?= $translations['payment-success-heading'] ?></h1>
    <p><?= sprintf($translations['payment-success-stripe-message'], htmlspecialchars($commandeId)) ?></p>
    <a href="../../index.php" class="btn"><?= $translations['payment-success-back-home'] ?></a>
</div>
<?php include_once("../../INCLUDES/footer.php"); ?>
</body>
</html>
