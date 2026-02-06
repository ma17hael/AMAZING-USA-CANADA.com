<?php

define('GOOGLE_CLIENT_ID', '778158757538-8c1kpvmnf1o6aqe9j1f83aj1rnu6prbl.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI', 'http://localhost/INCLUDES/oauth.php?provider=google');

define('FACEBOOK_CLIENT_ID', '1210338254082277');
define('FACEBOOK_CLIENT_SECRET', 'aedeb154b5b50a80f00254d4d2ef225e');
define('FACEBOOK_REDIRECT_URI', 'http://localhost/INCLUDES/oauth.php?provider=facebook');

// Empêche l'accès direct au fichier
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    http_response_code(403);
    exit("Accès interdit");
}

$host = 'localhost'; //Serveur de Base de données
$dbname = 'amazingusacanada'; //Nom de la base de données
$username = 'AMAZINGUSASITE'; //Utilisateur MySQL
$password = 'Am@zingUSA2025'; //Mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    //Activer le mode d'erreur pour voir les exceptions PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : ".$e->getMessage());
}
?>