<?php
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';

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
                $_SESSION['register_email'] = $email;
                $_SESSION['register_code'] = $code;
                $step = 2;

                // Envoyer email
                $subject = "Votre code de validation";
                $message = "Bonjour,\n\nVotre code pour créer votre compte est : $code\n\nMerci.";
                $headers = "From: no-reply@amazing-usa-canada.com";

                if (!mail($email, $subject, $message, $headers)) {
                    $error = "Impossible d'envoyer l'email. Vérifiez la configuration.";
                    $step = 1;
                }
            }
        }

        // Étape 2 : validation code + mot de passe
    } elseif (isset($_POST['code'], $_POST['password'])) {
        if (!isset($_SESSION['register_email'], $_SESSION['register_code'])) {
            header('Location: register.php');
            exit;
        }

        $code     = $_POST['code'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$code || !$password) {
            $error = "Code et mot de passe requis.";
            $step = 2;
        } elseif ($code != $_SESSION['register_code']) {
            $error = "Code invalide.";
            $step = 2;
        } else {
            $email = $_SESSION['register_email'];
            $hash  = password_hash($password, PASSWORD_DEFAULT);

            // Créer l'utilisateur
            $stmt = $pdo->prepare("INSERT INTO Utilisateurs (Mail, MotdePasse) VALUES (:email, :password)");
            $stmt->execute(['email' => $email, 'password' => $hash]);

            // Nettoyer session
            unset($_SESSION['register_email'], $_SESSION['register_code']);

            header('Location: login.php?success=1');
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
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <div class="auth-form">
        <?php if ($step === 1): ?>
            <form method="POST">
                <h2>Créer un compte</h2>
                <label>Email :</label>
                <input type="email" name="email" required>
                <button type="submit">Suivant</button>
            </form>
        <?php elseif ($step === 2): ?>
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