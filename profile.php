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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_account'])) {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if (empty($email)) {
        $error = "L'adresse e-mail est obligatoire.";
    } else {
        $params = [
            'mail' => $email,
            'username' => $username,
            'id' => $_SESSION['user_id']
        ];

        $sql = "UPDATE Utilisateurs SET Mail = :mail, Username = :username";

        // Gestion du mot de passe
        if (!empty($password)) {
            if ($password !== $passwordConfirm) {
                $error = "Les mots de passe ne correspondent pas.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql .= ", MotDePasse = :password";
                $params['password'] = $hash;
            }
        }

        // Gestion de l'image
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
            $imageData = file_get_contents($_FILES['picture']['tmp_name']);
            $sql .= ", UserPicture = :picture";
            $params['picture'] = $imageData;
            $_SESSION['user_picture'] = $imageData;
        }

        $sql .= " WHERE ID_Users = :id";

        if (!isset($error)) {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            header('Location: profile.php?success=1');
            exit;
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account'])) {

    $stmt = $pdo->prepare("DELETE FROM Utilisateurs WHERE ID_Users = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);

    session_destroy();

    header('Location: index.php');
    exit;
}

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
            <h1><?=$translations['profile-main-title']?></h1>
        </section>

        <!-- SECTION PROFIL UNIQUEMENT -->
        <section class="profile-essentials">
            <div class="profile-left">
                <?php
                if ($user['UserPicture'] != null) {
                    $imageBase64 = base64_encode($user['UserPicture']);
                    $imageSrc = 'data:image/jpeg;base64,' . $imageBase64;
                }
                ?>
                <img src="<?= $imageSrc ?? 'INCLUDES/ICONS/user.svg' ?>"
                    alt="Photo de profil"
                    class="profile-avatar">

                <h2><?= htmlspecialchars($displayName) ?></h2>
                <p><?= htmlspecialchars($user['Mail']) ?></p>
            </div>

            <div class="profile-right">
                <h2><?=$translations['profile-action-title']?></h2>

                <form method="POST" enctype="multipart/form-data" class="profile-form">
                    <div class="form-group">
                        <label for="email"><?=$translations['profile-mail-field']?></label>
                        <input type="email" id="email" name="email" value="<?= $user['Mail'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="username"><?=$translations['profile-username-field']?></label>
                        <input type="text" id="username" name="username" value="<?= $user['Username'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="picture"><?=$translations['profile-picture-field']?></label>
                        <input type="file" id="picture" name="picture">
                    </div>
                    <div class="form-group">
                        <label for="password"><?=$translations['profile-password-field']?></label>
                        <input type="password" id="password" name="password" value="">
                    </div>
                    <div class="form-group">
                        <label for="password_confirm"><?=$translations['profile-passwordconfir-field']?></label>
                        <input type="password" id="password_confirm" name="password_confirm" value="">
                    </div>

                    <button type="submit" class="btn-save"><?=$translations['profile-save-change']?></button>
                </form>
                <form method="POST" onsubmit="return confirm('Cette action est définitive. Supprimer votre compte ?');">
                    <input type="hidden" name="delete_account" value="1">
                    <button type="submit" class="btn-danger">
                        <?=$translations['profile-discard-account']?>
                    </button>
                </form>
            </div>
        </section>

        <section class="profile-main-title">
            <h1><?=$translations['profile-purchased-maps']?></h1>
        </section>

        <!-- SECTION CARTES ACHETÉES (EN DESSOUS) -->
        <section class="profile-purchases-section">
            <div class="cards-grid">
                <?php if (empty($MapBought)): ?>
                    <p><?=$translations['profile-nopurchased-maps']?></p>
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
                            <div class="mapcard-actions">
                                <a href="voirmap.php?id=<?= htmlspecialchars($carte['ID_Map']) ?>"
                                    class="btn-map">
                                    <?=$translations['profile-see-map']?>
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