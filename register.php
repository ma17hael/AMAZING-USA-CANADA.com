<?php
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';
require 'INCLUDES/LIBRAIRIES/PHPMailer-7.0.2/src/SMTP.php';
require 'INCLUDES/LIBRAIRIES/PHPMailer-7.0.2/src/PHPMailer.php';
require 'INCLUDES/LIBRAIRIES/PHPMailer-7.0.2/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = '';
$step = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Étape 1 : saisie email
    if (isset($_POST['email']) && !isset($_POST['code'])) {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

        if (!$email) {
            $error = "Email requis.";
        } else {
            // Vérifier si email déjà utilisé
            $stmt = $pdo->prepare("SELECT ID_Users FROM Utilisateurs WHERE Mail = :mail");
            $stmt->execute(['mail' => $email]);

            if ($stmt->fetch()) {
                $error = "Cet email est déjà utilisé.";
            } else {
                // Générer code à 6 chiffres
                $code = rand(100000, 999999);
                $_SESSION['register_code'] = $code;
                $_SESSION['register_email'] = $email;
                $step = 2;

                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = SMTP_HOST;
                    $mail->SMTPAuth = true;
                    $mail->Username = SMTP_USER;
                    $mail->Password = SMTP_PASS;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = SMTP_PORT;

                    $mail->CharSet = 'UTF-8';
                    $mail->Encoding = 'base64';

                    $mail->setFrom('no-reply@amazing-usa-canada.com', 'AMAZING USA & CANADA');
                    $mail->addAddress($email);

                    $mail->isHTML(false);
                    $mail->Subject = 'Bienvenue sur AMAZING-USA-CANADA.com';
                    $mail->Body =
                        "Bonjour, chère nouvelle utilisateur \n\n" .
                        "Prépare-toi a trouvez toutes sorte de zones aussi incroyables et inconnu les uns que les autres ! \n\n" .
                        "Voici ton code de création de compte : " . $code . "\n\n" .
                        "Amuse toi bien !";

                    $mail->send();
                } catch (Exception $e) {
                    $error = "Impossible d'envoyer l'email. Réessayez plus tard.";
                    $step = 1;
                }
            }
        }

        // Étape 2 : validation code + mot de passe
    } elseif (isset($_POST['code'])) {
        if (!isset($_SESSION['register_code'])) {
            header('Location: register.php');
            exit;
        }
        $code = $_POST['code'] ?? null;

        if (!$code) {
            $error = "Code requis.";
            $step = 2;
        } elseif ($code != $_SESSION['register_code']) {
            $error = "Code invalide.";
            $step = 2;
        } else {
            $step = 3;
        }
    } elseif (isset($_POST['password'], $_POST['passwordconfirm'])) {
        if (!isset($_SESSION['register_email'], $_SESSION['register_code'])) {
            header('Location: register.php');
            exit;
        }
        $password = $_POST['password'] ?? null;
        $passwordconfirm = $_POST['passwordconfirm'] ?? null;

        if (!$password || !$passwordconfirm) {
            $error = "Mot de passe requis";
            $step = 3;
        } elseif ($password != $passwordconfirm) {
            $error = "Les mots de passe ne sont pas identiques";
            $step = 3;
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $_SESSION['passwordhash'] = $hash;
            $step = 4;
        }
    } elseif (isset($_POST['pseudo']) || isset($_FILES['profilepicture'])) {
        if (!isset($_SESSION['register_email'], $_SESSION['passwordhash'])) {
            header('Location: register.php');
            exit;
        }

        $email    = $_SESSION['register_email'];
        $passwordhash = $_SESSION['passwordhash'];
        $pseudo   = $_POST['pseudo'] ?? null;
        $file     = $_FILES['profilepicture'] ?? null;
        $imageData = null;

        if ($pseudo) {
            $_SESSION['register_pseudo'] = $pseudo;
        }

        $filename = null;
        if ($file && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($file['error'] === UPLOAD_ERR_OK) {
                $imageData = file_get_contents($_FILES['profilepicture']['tmp_name']);
            } else {
                $error = "Erreur lors de l'upload de l'image.";
                $step = 4;
            }
        }
        if (!$error) {
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (Username, Mail, MotdePasse) VALUES (:username, :mail, :password)");
            $stmt->execute([
                'username' => $pseudo,
                'mail' => $email,
                'password' => $passwordhash
            ]);

            $userId = $pdo->lastInsertId();
            $_SESSION['user_id'] = $pdo->lastInsertId();

            if ($imageData) {
                $stmt = $pdo->prepare("UPDATE utilisateurs SET UserPicture = :image WHERE ID_Users = :id");
                $stmt->execute([
                    'image' => $imageData,
                    'id' => $userId
                ]);
            }
            unset($_SESSION['register_email'], $_SESSION['register_code'], $_SESSION['passwordhash']);

            $_SESSION['user_picture'] = $imageData;

            header('Location: profile.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <title>Inscription - AMAZING-USA-CANADA.com</title>
    <link rel="stylesheet" href="CSS/register.css">
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="icon" href="INCLUDES/ICONS/favicon.ico" type="image/x-icon">
</head>

<body>
    <?php include_once("INCLUDES/header.php"); ?>
    <main>
        <div class="auth-form">
            <?php if ($step === 1): ?>
                <form method="POST">
                    <h2>Créer un compte</h2>
                    <label>Email :</label>
                    <input type="email" name="email" required>
                    <?php if ($error): ?>
                        <p class="error"><?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>
                    <button type="submit">Suivant</button>
                </form>
            <?php elseif ($step === 2): ?>
                <form method="POST">
                    <h2>Créer un compte</h2>
                    <p>Vous allez recevoir un code à l'addresse mail rensignée plutôt</p>
                    <label>Code :</label>
                    <input type="text" name="code" required>
                    <?php if ($error): ?>
                        <p class="error"><?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>
                    <button type="submit">Suivant</button>
                </form>
            <?php elseif ($step === 3): ?>
                <form method="POST">
                    <h2>Créer un compte</h2>
                    <label>Mot de passe</label>
                    <input type="password" name="password" required>
                    <label>Confirmer le mot de passe :</label>
                    <input type="password" name="passwordconfirm" required>
                    <?php if ($error): ?>
                        <p class="error"><?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>
                    <button type="submit">Suivant</button>
                </form>
            <?php elseif ($step === 4): ?>
                <form method="POST" enctype="multipart/form-data">
                    <label>Pseudo :</label>
                    <input type="text" name="pseudo">
                    <label>Photo de profil</label>
                    <input type="file" name="profilepicture">
                    <?php if ($error): ?>
                        <p class="error"><?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>
                    <button type="submit">Suivant</button>
                </form>
            <?php endif; ?>
            <p class="auth-switch">
                Déjà inscrit ?
                <a href="login.php">Se connecter</a>
            </p>
        </div>
    </main>
    <?php include_once("INCLUDES/footer.php"); ?>
</body>

</html>