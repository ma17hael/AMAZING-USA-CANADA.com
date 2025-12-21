<?php
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? null;

    if (!$email || !$password) {
        $error = "Email et mot de passe requis.";
    } else {
        /* Concernant la connexion */
        if ($action === 'login') {
            $stmt = $pdo->prepare(
                "SELECT ID_Users, MotdePasse FROM Utilisateurs WHERE Mail = :mail;"
            );
            $stmt->execute(['mail' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['MotdePasse'])) {
                $_SESSION['user_id'] = $user['ID_Users'];
                header('Location: profile.php');
                exit;
            } else {
                $error = "Identifiants incorrects.";
            }
        } elseif ($action === 'register') {
            $check = $pdo->prepare(
                "SELECT ID_Users FROM Utilisateurs WHERE Mail = :mail;"
            );
            $check->execute(['mail' => $email]);

            if ($check->fetch()) {
                $error = "Cet email est déja utilisé.";
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
                header('Location: auth.php?success=1');
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription/Connexion - AMAZING-USA-CANADA.com</title>
    <link rel="stylesheet" href="CSS/auth.css">
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="icon" href="INCLUDES/ICONS/favicon.ico" type="image/x-icon">
</head>

<body>
    <?php include_once("INCLUDES/header.php") ?>
    <main>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif ?>
        <div class="auth-container">
            <form method="POST" action="auth.php" class="auth-form">
                <h2>Connexion</h2>
                <input type="hidden" name="action" value="login">

                <label for="login-email">Email :</label>
                <input type="email" id="login-email" name="email" required>

                <label for="login-password">Mot de passe :</label>
                <input type="password" id="login-password" name="password" required>

                <button type="submit">Se connecter</button>
            </form>
            <form method="POST" action="auth.php" class="auth-form">
                <h2>Créer un compte</h2>
                <input type="hidden" name="action" value="register">

                <label for="register-email">Email <span class="required">*</span> :</label>
                <input type="email" id="register-email" name="email" required>

                <label for="register-password">Mot de passe <span class="required">*</span> :</label>
                <input type="password" id="register-password" name="password" required>

                <button type="submit">Crée un compte</button>
            </form>
        </div>
    </main>
    <?php include_once("INCLUDES/footer.php") ?>
</body>

</html>