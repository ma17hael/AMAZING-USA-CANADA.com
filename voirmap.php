<?php
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Carte invalide.');
}

$mapId = (int) $_GET['id'];

$sql = "SELECT Map_NameFR, Map_NameEN
        FROM statesmap
        WHERE ID_Map = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $mapId]);
$map = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$map) {
    die('Carte introuvable.');
}

$stmt = $pdo->prepare("
    SELECT Data FROM statesmap_data 
    WHERE ID_Map = :id_map AND Active = 1
    ORDER BY Version DESC, Date_Import DESC
    LIMIT 1
");
$stmt->execute([':id_map' => $mapId]);
$geojson = $stmt->fetchColumn();

if (!$geojson) {
    die("Aucun GeoJSON trouvé pour cette carte.");
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($map["MapName$langBDD"]) ?></title>
    <link rel="stylesheet" href="CSS/voirmap.css">
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="icon" href="INCLUDES/ICONS/favicon.ico" type="image/x-icon">
</head>

<body>
    <?php include_once("INCLUDES/header.php") ?>
    <div class="map-header">
        <div class="map-title"><?= htmlspecialchars($map["Map_Name$langBDD"]) ?></div>
    </div>

    <div class="map-container" id="map"></div>
    <?php include_once("INCLUDES/footer.php") ?>
</body>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([48.8566, 2.3522], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    var geojsonData = <?= json_encode(json_decode($geojson)) ?>;

    function getIcon(url) {
        return L.icon({
            iconUrl: url ? 'INCLUDES/MAPSICONS/'+ url : "ICONS/default.png",
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
        });
    }

    var geoLayer = L.geoJSON(geojsonData, {
        pointToLayer: function(feature, latlng) {
            return L.marker(latlng, {
                icon: getIcon(feature.properties.icon)
            });
        },
        onEachFeature: function(feature, layer) {
            var popupContent = "<strong>" + feature.properties.name + "</strong><br>" +
                feature.properties.description;
            layer.bindPopup(popupContent);
        }
    }).addTo(map);

    if (geoLayer.getBounds().isValid()) {
        map.fitBounds(geoLayer.getBounds());
    }
</script>

</html>