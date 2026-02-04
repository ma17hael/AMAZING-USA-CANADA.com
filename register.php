<?php
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? null;

    if (!$email || !$password) {
        $error = "Email et mot de passe requis.";
    } else {
        $check = $pdo->prepare(
            "SELECT ID_Users FROM Utilisateurs WHERE Mail = :mail;"
        );
        $check->execute(['mail' => $email]);

        if ($check->fetch()) {
            $error = "Cet email est déjà utilisé.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                "INSERT INTO Utilisateurs (Mail, MotdePasse)
                 VALUES (:email, :password);"
            );
            $stmt->execute([
                'email' => $email,
                'password' => $hash
            ]);

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

        <form method="POST" class="auth-form">
            <h2>Créer un compte</h2>

            <label>Email :</label>
            <input type="email" name="email" required>

            <label>Mot de passe :</label>
            <input type="password" name="password" required>

            <button type="submit">Créer le compte</button>

            <p class="auth-switch">
                Déjà inscrit ?
                <a href="login.php">Se connecter</a>
            </p>
        </form>
    </main>

    <?php include_once("INCLUDES/footer.php"); ?>
</body>

</html>