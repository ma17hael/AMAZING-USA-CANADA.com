<?php
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? null;

    /* ===== Vérification CAPTCHA ===== */
    $recaptchaSecret  = '6LdJY2AsAAAAANzCFvCcgq3-AofYPxTUTGt_XI99';
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? null;

    if (!$recaptchaResponse) {
        $error = "Veuillez valider le CAPTCHA.";
    } else {

        $verify = file_get_contents(
            "https://www.google.com/recaptcha/api/siteverify?secret="
            . urlencode($recaptchaSecret)
            . "&response="
            . urlencode($recaptchaResponse)
            . "&remoteip="
            . $_SERVER['REMOTE_ADDR']
        );

        $captchaResult = json_decode($verify);

        if (!$captchaResult || !$captchaResult->success) {
            $error = "Échec de la vérification du CAPTCHA.";
        }
    }

    /* ===== Vérification identifiants ===== */
    if (empty($error)) {

        if (!$email || !$password) {
            $error = "Adresse e-mail et mot de passe requis.";
        } else {

            $stmt = $pdo->prepare(
                "SELECT ID_Users, MotdePasse FROM Utilisateurs WHERE Mail = :mail;"
            );
            $stmt->execute(['mail' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['MotdePasse'])) {

                $_SESSION['user_id'] = $user['ID_Users'];

                $stmt = $pdo->prepare(
                    "SELECT UserPicture FROM Utilisateurs WHERE ID_Users = :id"
                );
                $stmt->execute(['id' => $user['ID_Users']]);
                $_SESSION['user_picture'] = $stmt->fetchColumn();

                header('Location: profile.php');
                exit;

            } else {
                $error = "Identifiants incorrects.";
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <title><?= $translations['login-title'] ?> - AMAZING-USA-CANADA.com</title>
    <link rel="stylesheet" href="CSS/login.css">
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="icon" href="INCLUDES/ICONS/favicon.ico" type="image/x-icon">
</head>

<body>
    <?php include_once("INCLUDES/header.php"); ?>

    <main>
        <form method="POST" class="auth-form">
            <h2><?= $translations['login-title'] ?></h2>
            <label><?= $translations['login-email-label'] ?></label>
            <input type="email" name="email" required>
            <label><?= $translations['login-password-label'] ?></label>
            <input type="password" name="password" required>
            <div class="captcha">
                <div class="g-recaptcha" data-sitekey="6LdJY2AsAAAAAOGijqAfwaAAp5SbA_VSeY6kAFB1"></div>
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            </div>
            <?php if ($error): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <button type="submit"><?= $translations['login-submit'] ?></button>
            <p class="auth-switch">
                <?= $translations['login-no-account'] ?>
                <a href="register.php"><?= $translations['login-create-account'] ?></a>
            </p>
            <p class="auth-switch">
                <a href="forgotpassword.php"><?= $translations['login-forgot-password'] ?></a>
            </p>
        </form>
    </main>

    <?php include_once("INCLUDES/footer.php"); ?>
</body>
</html>