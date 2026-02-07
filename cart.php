<?php
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT sm.ID_Map, sm.StateMap, sm.Map_NameFR, sm.Map_NameEN, mt.Libelle_TypeFR, mt.Libelle_TypeEN
    FROM statesmap sm
    INNER JOIN maptypes mt ON mt.ID_TypeMap = sm.Map_Type
    INNER JOIN commandesdetails cd ON cd.IDMap = sm.ID_Map
    INNER JOIN commandes c ON c.ID_Commande = cd.IDCommande
    WHERE c.ID_Users = ? AND c.CommandeStatus = 1");
$stmt->execute([$userId]);
$CartMap = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT c.Prix_Total
    FROM commandes c
    WHERE c.CommandeStatus = 1 AND c.ID_Users = ?");
$stmt->execute([$userId]);
$CartPrice = $stmt->fetch(PDO::FETCH_ASSOC);
$prixTotal = $CartPrice['Prix_Total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Panier</title>
    <!-- Liens CSS -->
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/cart.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <!-- FAVICON -->
    <link rel="icon" href="INCLUDES/ICONS/favicon.ico" type="image/x-icon">
</head>

<body>
    <?php include_once("INCLUDES/header.php"); ?>
    <div class="panier-container">
        <section class="panier-liste">
            <h1>Votre panier</h1>
            <?php if (isset($_SESSION['message_panier'])): ?>
                <div class="panier-message">
                    <?= htmlspecialchars($_SESSION['message_panier']) ?>
                </div>
                <?php unset($_SESSION['message_panier']); ?>
            <?php endif; ?>
            <?php if (empty($CartMap)): ?>
                <p class="panier-vide">Votre panier est actuellement vide.</p>
            <?php endif; ?>
            <?php foreach ($CartMap as $article):
                $imageBase64 = base64_encode($article['StateMap']);
                $imageSrc = 'data:image/jpeg;base64,' . $imageBase64; ?>
                <article class="panier-carte">
                    <img src="<?= $imageSrc ?>"
                        alt="<?= htmlspecialchars($article["Map_Name$langBDD"]) ?>">
                    <div class="panier-info">
                        <h2><?= htmlspecialchars($article["Map_Name$langBDD"]) ?></h2>
                        <p><?= htmlspecialchars($article["Libelle_Type$langBDD"]) ?></p>
                    </div>
                    <div class="panier-actions">
                        <a href="mapsdetails.php?id=<?= $article['ID_Map'] ?>" class="btn voir">
                            Voir
                        </a>
                        <a href="deletecart.php?id=<?=$article['ID_Map']?>"
                            class="btn supprimer"
                            onclick="return confirm('Supprimer cet article du panier ?');">
                            Supprimer
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
        <aside class="panier-resume">
            <h2>Résumé</h2>
            <p class="total">
                Total :
                <strong><?= number_format($prixTotal, 2, ',', ' ') ?> €</strong>
            </p>
            <?php if (!empty($CartMap)): ?>
                <a href="commande.php" class="btn commander">
                    Passer la commande
                </a>
                <a href="stopcart.php"
                    class="btn annuler-panier"
                    onclick="return confirm('Voulez-vous vraiment annuler votre panier ? Cette action est irréversible.');">
                    Annuler le panier
                </a>
            <?php endif; ?>
        </aside>
    </div>
    <?php include_once('INCLUDES/footer.php'); ?>
</body>
</html>