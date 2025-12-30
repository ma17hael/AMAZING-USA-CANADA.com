<?php
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit;
}

$stmt = $pdo->prepare(
    "SELECT * FROM Utilisateurs WHERE ID_Users = :id;"
);
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$displayName = !empty($user['Username'])
    ? $user['Username']
    : $user['Mail'];

$stmt = $pdo->prepare("
    SELECT
        C.ID_Commande,
        LC.IDMap,
        M.*,
        MT.*,
        L.*
    FROM Commandes C
    INNER JOIN CommandesDetails LC ON LC.IDCommande = C.Id_Commande
    INNER JOIN StatesMap M ON M.ID_Map = LC.IDMap
    INNER JOIN MapTypes MT ON MT.Id_TypeMap = M.Map_Type
    INNER JOIN Localisation L ON L.ID_Localisation = M.Approx_Localisation
    WHERE C.ID_Users = :id;
");
$stmt->execute(['id' => $_SESSION['user_id']]);
$MapBought = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de <?= htmlspecialchars($displayName) ?> - AMAZING-USA-CANADA.com</title>
    <link rel="stylesheet" href="CSS/profile.css">
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="icon" href="INCLUDES/ICONS/favicon.ico" type="image/x-icon">
</head>

<body>
    <?php include_once("INCLUDES/header.php") ?>
    <main>

        <!-- BANDEAU TITRE -->
        <section class="profile-main-title">
            <h1>Mon profil</h1>
        </section>

        <!-- SECTION PROFIL UNIQUEMENT -->
        <section class="profile-essentials">

            <div class="profile-card">

                <div class="profile-header">
                    <?php
                    $imageBase64 = base64_encode($user['UserPicture']);
                    $imageSrc = 'data:image/jpeg;base64,' . $imageBase64;
                    ?>
                    <img src="<?= $imageSrc ?? 'INCLUDES/ICONS/user.svg' ?>"
                        alt="Photo de profil"
                        class="profile-avatar">

                    <h2><?= htmlspecialchars($displayName) ?></h2>
                    <p><?= htmlspecialchars($user['Mail']) ?></p>
                </div>

                <div class="profile-actions">
                    <button class="btn-primary" id="editProfileBtn">
                        Modifier mes informations
                    </button>

                    <form method="POST" action="delete_account.php"
                        onsubmit="return confirm('Confirmer la suppression du compte ?');">
                        <button class="btn-danger">
                            Supprimer mon compte
                        </button>
                    </form>
                </div>

            </div>

        </section>

        <section class="profile-main-title">
            <h1>Les cartes à mon nom</h1>
        </section>

        <!-- SECTION CARTES ACHETÉES (EN DESSOUS) -->
        <section class="profile-purchases-section">

            <h2>Mes cartes achetées</h2>

            <div class="cards-grid">
                <?php if (empty($MapBought)): ?>
                    <p>Aucune carte achetée pour le moment.</p>
                <?php else: ?>
                    <?php foreach ($MapBought as $carte): ?>
                        <?php
                        $imageBase64 = base64_encode($carte['StateMap']);
                        $imageSrc = 'data:image/jpeg;base64,' . $imageBase64;
                        ?>
                        <article class="mapcard">
                            <img src="<?= $imageSrc ?>"
                                alt="<?= htmlspecialchars($carte["Map_Name$langBDD"]) ?>"
                                data-modal-image>

                            <h3><?= htmlspecialchars($carte["Map_Name$langBDD"]) ?></h3>

                            <p>
                                <strong><?= $translations['home-mapshowcase-card-type'] ?></strong>
                                <?= htmlspecialchars($carte["Libelle_Type$langBDD"]) ?>
                            </p>

                            <p>
                                <strong><?= $translations['home-mapshowcase-card-localisation'] ?></strong>
                                <?= htmlspecialchars($carte["LibelleLocalisation$langBDD"]) ?>
                            </p>

                            <p>
                                <strong><?= $translations['home-mapshowcase-card-price'] ?></strong>
                                <?= htmlspecialchars($carte['Prix']) ?>
                                <?= $translations['home-mapshowcase-card-money'] ?>
                            </p>

                            <div class="mapcard-actions">
                                <a href="voirmap.php?id=<?= htmlspecialchars($carte['ID_Map']) ?>"
                                    class="btn-map">
                                    Voir la carte
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </section>

    </main>

    <?php include_once("INCLUDES/footer.php") ?>
</body>

</html>