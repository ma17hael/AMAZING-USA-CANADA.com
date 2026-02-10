<?php
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = (int)$_SESSION['user_id'];

/* Récupérer la commande active */
$stmt = $pdo->prepare("
    SELECT ID_Commande, Prix_Total
    FROM commandes
    WHERE ID_Users = ? AND CommandeStatus = 1
    LIMIT 1
");
$stmt->execute([$userId]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande) {
    $_SESSION['message_panier'] = $translations['purchasing-empty-message'];
    header('Location: cart.php');
    exit;
}

/* Récupérer les articles */
$stmt = $pdo->prepare("
    SELECT sm.ID_Map, sm.Map_NameFR, sm.Map_NameEN, mt.Libelle_TypeFR, mt.Libelle_TypeEN, sm.StateMap, sm.Prix
    FROM commandesdetails cd
    INNER JOIN statesmap sm ON sm.ID_Map = cd.IDMap
    INNER JOIN maptypes mt ON mt.ID_TypeMap = sm.Map_Type
    WHERE cd.IDCommande = ?
");
$stmt->execute([$commande['ID_Commande']]);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $translations['purchasing-title'] ?></title>
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/mapslist.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="icon" href="INCLUDES/ICONS/favicon.ico" type="image/x-icon">
    <style>
        .paiement-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            max-width: 1200px;
            margin: 40px auto;
        }
        .paiement-liste { flex: 2; display: grid; grid-template-columns: repeat(auto-fill, minmax(280px,1fr)); gap: 20px; }
        .paiement-resume { flex: 1; background:#f8fafc; padding:30px; border-radius:20px; box-shadow:0 4px 6px rgba(0,0,0,0.1);}
        .btn-map { padding: 12px 24px; border:none; border-radius:12px; cursor:pointer; margin-top:15px; width:100%; font-weight:600; font-size:1rem;}
        .stripe { background:linear-gradient(135deg,#2563eb,#1e40af); color:#fff;}
        .paypal { background:#ffc439; color:#111; }
    </style>
</head>
<body>
<?php include_once("INCLUDES/header.php"); ?>

<div class="paiement-container">
    <section class="paiement-liste">
        <h1><?= $translations['purchasing-cart-title'] ?></h1>
        <?php foreach($articles as $article): 
            $imageBase64 = base64_encode($article['StateMap']);
            $imageSrc = 'data:image/jpeg;base64,' . $imageBase64; ?>
            <div class="mapcard">
                <img src="<?= $imageSrc ?>" alt="<?= htmlspecialchars($article["Map_NameFR"]) ?>">
                <h3><?= htmlspecialchars($article["Map_NameFR"]) ?></h3>
                <p><?= htmlspecialchars($article["Libelle_TypeFR"]) ?></p>
                <p><strong><?= number_format($article['Prix'], 2, ',', ' ') ?> €</strong></p>
            </div>
        <?php endforeach; ?>
    </section>

    <aside class="paiement-resume">
        <h2><?= $translations['purchasing-summary-title'] ?></h2>
        <p>Total : <strong><?= number_format($commande['Prix_Total'], 2, ',', ' ') ?> €</strong></p>

        <!-- Stripe -->
        <form action="PURCHASE/purchase_stripe.php" method="post">
            <input type="hidden" name="commande_id" value="<?= $commande['ID_Commande'] ?>">
            <button type="submit" class="btn-map stripe"><?= $translations['purchasing-pay-card'] ?></button>
        </form>

        <!-- PayPal -->
        <form action="PURCHASE/purchase_paypal.php" method="post">
            <input type="hidden" name="commande_id" value="<?= $commande['ID_Commande'] ?>">
            <button type="submit" class="btn-map paypal"><?= $translations['purchasing-pay-paypal'] ?></button>
        </form>
    </aside>
</div>

<?php include_once("INCLUDES/footer.php"); ?>
</body>
</html>
