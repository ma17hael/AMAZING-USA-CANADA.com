<?php
define('SMTP_HOST', 'ssl0.ovh.net');
define('SMTP_USER', 'no-reply@amazing-usa-canada.com');
define('SMTP_PASS', 'AutomateU5AC@NAD@2026');
define('SMTP_PORT', 587);
define('SMTP_CONTACTHOST', 'ssl0.ovh.net');
define('SMTP_CONTACTUSER', 'support@amazing-usa-canada.com');
define('SMTP_CONTACTPASS', 'SupportU5AC@NAD@2026');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Empêche l'accès direct au fichier
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    http_response_code(403);
    exit("Accès interdit");
}

$host = 'amazinydb.mysql.db'; //Serveur de Base de données
$dbname = 'amazinydb'; //Nom de la base de données
$username = 'amazinydb'; //Utilisateur MySQL
$password = 'Amaziny2026'; //Mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    //Activer le mode d'erreur pour voir les exceptions PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : ".$e->getMessage());
}
?>