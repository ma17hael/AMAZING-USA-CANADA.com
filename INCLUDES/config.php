<?php
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