<?php
require_once 'INCLUDES/init.php';
require_once 'INCLUDES/config.php';
require 'INCLUDES/LIBRAIRIES/PHPMailer-7.0.2/src/SMTP.php';
require 'INCLUDES/LIBRAIRIES/PHPMailer-7.0.2/src/PHPMailer.php';
require 'INCLUDES/LIBRAIRIES/PHPMailer-7.0.2/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = '';
$messageClass = '';

if (isset($_POST['forgot_submit'])) {
    $email = trim($_POST['email']);

    // Vérifier que l'email existe
    $stmt = $pdo->prepare("SELECT ID_Users FROM utilisateurs WHERE Mail = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Générer mot de passe aléatoire
        $newPassword = bin2hex(random_bytes(4)); // 8 caractères
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Mettre à jour dans la base
        $update = $pdo->prepare("UPDATE utilisateurs SET MotdePasse = ? WHERE ID_Users = ?");
        $update->execute([$hashedPassword, $user['ID_Users']]);

        // Envoyer mail via PHPMailer
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

            $mail->setFrom('noreply@tonsite.com', 'AMAZING-USA-CANADA');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Votre nouveau mot de passe';
            $mail->Body    = "<p>Votre nouveau mot de passe est : <strong>$newPassword</strong></p>
                              <p>Nous vous recommandons de le modifier après votre connexion.</p>";

            $mail->send();
            $message = "Un nouveau mot de passe vous a été envoyé par email.";
            $messageClass = 'success';
            header("Location: login.php");
        } catch (Exception $e) {
            $message = "Erreur lors de l’envoi : " . $mail->ErrorInfo;
            $messageClass = 'error';
        }
    } else {
        $message = "Cet email n’existe pas.";
        $messageClass = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?? 'fr' ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe - AMAZING-USA-CANADA</title>
    <link rel="stylesheet" href="CSS/forgotpassword.css">
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <!-- FAVICON -->
    <link rel="icon" href="INCLUDES/ICONS/favicon.ico" type="image/x-icon">
</head>

<body>
    <?php include_once("INCLUDES/header.php"); ?>

    <main class="reset-password-main">
        <section class="reset-password-section">
            <div class="reset-password-container">
                <div class="reset-header">
                    <img src="INCLUDES/ICONS/lock.svg" alt="Sécurité" class="reset-icon">
                    <h2>Réinitialisation du mot de passe</h2>
                    <p>Entrez votre adresse e-mail ci-dessous. Un mot de passe temporaire vous sera envoyé.</p>
                </div>

                <?php if ($message): ?>
                    <p class="<?= $messageClass ?>"><?= $message ?></p>
                <?php endif; ?>

                <form method="POST">
                    <input type="email" name="email" placeholder="Votre email" required>
                    <button type="submit" name="forgot_submit">Recevoir un nouveau mot de passe</button>
                </form>

                <p class="back-login"><a href="login.php">Retour à la page de connexion</a></p>
            </div>
        </section>
    </main>

    <?php include_once("INCLUDES/footer.php"); ?>
</body>

</html>