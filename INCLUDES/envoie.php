<?php
// --- CONFIGURATION BDD ---
$host = 'amazinydb.mysql.db'; //Serveur de Base de données
$dbname = 'amazinydb'; //Nom de la base de données
$username = 'amazinydb'; //Utilisateur MySQL
$password = 'Amaziny2026'; //Mot de passe MySQL
$table = "statesmap_data";
// --- Connexion à la BDD ---
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Connexion BDD échouée : " . $e->getMessage());
}

// --- Traitement du formulaire ---
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['geojson_file']) && isset($_POST['id_map'])) {
        $id_map = (int)$_POST['id_map'];
        $file_tmp = $_FILES['geojson_file']['tmp_name'];
        $geojson_data = file_get_contents($file_tmp);

        if ($geojson_data) {
            $version = 1; // ou calculer la version automatiquement
            $stmt = $pdo->prepare("
                INSERT INTO $table (ID_Map, Data, Version, Date_Import, Active)
                VALUES (:id_map, :data, :version, NOW(), 1)
            ");
            if ($stmt->execute([
                ':id_map' => $id_map,
                ':data'   => $geojson_data,
                ':version' => $version
            ])) {
                $message = "GeoJSON inséré avec succès pour ID_Map = $id_map";
            } else {
                $message = "Erreur lors de l'insertion en BDD";
            }
        } else {
            $message = "Impossible de lire le fichier GeoJSON";
        }
    } else {
        $message = "Veuillez choisir un fichier et un ID_Map";
    }

    if (isset($_POST['create_full_order']) && isset($_POST['id_user'])) {
        $idUser = (int)$_POST['id_user'];

        try {
            $pdo->beginTransaction();

            // Vérifier que l'utilisateur existe
            $checkUser = $pdo->prepare("SELECT ID_Users FROM utilisateurs WHERE ID_Users = :id");
            $checkUser->execute([':id' => $idUser]);

            if (!$checkUser->fetch()) {
                throw new Exception("Utilisateur inexistant.");
            }

            // Création de la commande
            $stmt = $pdo->prepare("
            INSERT INTO commandes (ID_Users, Prix_Total, CommandeStatus, DateCreation)
            VALUES (:id_user, 705, 2, NOW())
        ");
            $stmt->execute([':id_user' => $idUser]);

            $idCommande = $pdo->lastInsertId();

            // Insérer toutes les cartes dans la commande (requête optimisée)
            $pdo->exec("
            INSERT INTO commandesdetails (IDCommande, IDMap)
            SELECT $idCommande, ID_Map
            FROM statesmap
        ");

            $pdo->commit();
            $message = "Commande complète créée avec succès.";
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = "Erreur : " . $e->getMessage();
        }
    }
}

// --- Récupérer la liste des cartes pour le select ---
$maps = $pdo->query("SELECT ID_Map, Map_NameFR FROM statesmap ORDER BY Map_NameFR")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Uploader GeoJSON</title>
    <style>
        body {
            font-family: Arial;
            margin: 50px;
        }

        form {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
        }

        input,
        select {
            width: 100%;
            margin-bottom: 15px;
            padding: 8px;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            color: green;
        }
    </style>
</head>

<body>
    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label for="geojson_file">Choisir un fichier GeoJSON :</label>
        <input type="file" name="geojson_file" id="geojson_file" accept=".geojson,.json" required>

        <label for="id_map">Sélectionner la carte :</label>
        <select name="id_map" id="id_map" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($maps as $map): ?>
                <option value="<?= $map['ID_Map'] ?>"><?= htmlspecialchars($map['Map_NameFR']) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Envoyer</button>
    </form>

    <hr>

    <form method="post">
        <label for="id_user">ID utilisateur :</label>
        <input type="number" name="id_user" required>

        <button type="submit" name="create_full_order">
            Créer une commande avec toutes les cartes
        </button>
    </form>
</body>

</html>