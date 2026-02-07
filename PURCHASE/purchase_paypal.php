<?php
include_once("../INCLUDES/init.php");
require_once '../INCLUDES/config.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['commande_id'])) {
    header('Location: panier.php');
    exit;
}

$userId = (int)$_SESSION['user_id'];
$commandeId = (int)$_POST['commande_id'];

/* Récupérer le total */
$stmt = $pdo->prepare("SELECT Prix_Total FROM commandes WHERE ID_Commande = ? AND ID_Users = ?");
$stmt->execute([$commandeId, $userId]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande) { header('Location: panier.php'); exit; }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Paiement PayPal</title>
<script src="https://www.paypal.com/sdk/js?client-id=AX46ywKwyWufWJOwRWNy9zQg3kwgawuJdHKL6Jnxjr7ncSY-8NJvWXpcQI0Wee6LOQPL95r3j4WtAuaJ&currency=EUR"></script>
</head>
<body>
<h1>Payer avec PayPal</h1>
<div id="paypal-button-container"></div>

<script>
paypal.Buttons({
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{ amount: { value: '<?= $commande['Prix_Total'] ?>' } }]
        });
    },
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            window.location.href = 'success_paypal.php?orderID=' + data.orderID + '&commande_id=<?= $commandeId ?>';
        });
    }
}).render('#paypal-button-container');
</script>
</body>
</html>