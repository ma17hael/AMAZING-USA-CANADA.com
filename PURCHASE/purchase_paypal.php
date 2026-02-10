<?php
include_once("../INCLUDES/init.php");
require_once '../INCLUDES/config.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['commande_id'])) {
    header('Location: ../cart.php');
    exit;
}

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

$paypalClientId = getenv('PAYPAL_CLIENT_ID');
if (!$paypalClientId) {
    $_SESSION['message_panier'] = "Configuration PayPal manquante.";
    header('Location: ../purchasing.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <title>Paiement PayPal</title>
    <script src="https://www.paypal.com/sdk/js?client-id=<?= urlencode($paypalClientId) ?>&currency=EUR"></script>
</head>

<body>
    <h1><?= $translations['payment-paypal-heading'] ?></h1>
    <div id="paypal-button-container"></div>

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?= $commande['Prix_Total'] ?>'
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    window.location.href = 'SUCESSS/sucess_paypal.php?orderID=' + data.orderID + '&commande_id=<?= $commandeId ?>';
                });
            }
        }).render('#paypal-button-container');
    </script>
</body>

</html>
