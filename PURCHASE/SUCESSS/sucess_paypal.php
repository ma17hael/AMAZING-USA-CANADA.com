<?php
include_once("../../INCLUDES/init.php");
require_once '../../INCLUDES/config.php';

if (!isset($_GET['orderID']) || !isset($_GET['commande_id'])) {
    header('Location: ../../cart.php');
    exit;
}

$orderID = $_GET['orderID'];
$commandeId = (int)$_GET['commande_id'];

/* Mettre à jour la commande comme payée */
$stmt = $pdo->prepare("UPDATE commandes SET CommandeStatus = 2 WHERE ID_Commande = ? AND CommandeStatus = 1");
$stmt->execute([$commandeId]);
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<title><?= $translations['payment-paypal-success-title'] ?></title>
<link rel="stylesheet" href="../../CSS/header.css">
<link rel="stylesheet" href="../../CSS/footer.css">
<style>
body { font-family: 'Segoe UI', sans-serif; background:#f8fafc; margin:0; padding:0;}
.container { max-width:600px; margin:80px auto; padding:30px; background:#fff; border-radius:20px; box-shadow:0 4px 6px rgba(0,0,0,0.1); text-align:center;}
h1 { color:#ffc439; margin-bottom:20px;}
p { font-size:1.1rem; color:#1e293b;}
a.btn { display:inline-block; margin-top:20px; padding:12px 24px; border-radius:12px; text-decoration:none; background:#ffc439; color:#111; font-weight:600; }
a.btn:hover { background:#e0a800; }
</style>
</head>
<body>
<?php include_once("../../INCLUDES/header.php"); ?>
<div class="container">
    <h1><?= $translations['payment-success-heading'] ?></h1>
    <p><?= sprintf($translations['payment-success-paypal-message'], htmlspecialchars($commandeId)) ?></p>
    <a href="../../index.php" class="btn"><?= $translations['payment-success-back-home'] ?></a>
</div>
<?php include_once("../../INCLUDES/footer.php"); ?>
</body>
</html>
