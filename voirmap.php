<?php
include_once("INCLUDES/init.php");
require_once 'INCLUDES/config.php';

$downloadDir = __DIR__ ."/INCLUDES/TRAILSMAP/";
$downloadUrlBase = "INCLUDES/TRAILSMAP/"; 

// Vérification que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit;
}

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

$mapName = $map["Map_NameEN"];

$fileName = $mapName . '.kml';
$filePath = $downloadDir . $fileName;

$downloadFile = null;
if (file_exists($filePath)) {
    $downloadFile = $downloadUrlBase . rawurlencode($fileName);
}

// Vérification que l'utilisateur a bien acheté cette carte
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM Commandes C
    INNER JOIN CommandesDetails CD ON CD.IDCommande = C.Id_Commande
    WHERE C.ID_Users = :user_id AND CD.IDMap = :map_id
");
$stmt->execute(['user_id' => $_SESSION['user_id'], 'map_id' => $mapId]);
$hasPurchased = $stmt->fetchColumn() > 0;

if (!$hasPurchased) {
    die('Vous devez acheter cette carte pour la visualiser.');
}

$stmt = $pdo->prepare("
    SELECT Data FROM statesmap_data 
    WHERE ID_Map = :id_map AND Active = 1
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
    <title><?= htmlspecialchars($map["Map_Name$langBDD"]) ?></title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="CSS/voirmap.css">
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="icon" href="INCLUDES/ICONS/favicon.ico" type="image/x-icon">
</head>

<body>
    <?php include_once("INCLUDES/header.php") ?>
    <div class="map-header">
        <div class="map-title"><?= htmlspecialchars($map["Map_Name$langBDD"]) ?></div>
        <?php if ($downloadFile): ?>
            <div class="map-download">
                <a href="<?= htmlspecialchars($downloadFile) ?>"
                    class="download-btn"
                    download>
                    Télécharger la carte des randonnées
                </a>
            </div>
        <?php endif; ?>
        <div class="map-search-container">
            <input type="text" id="map-search" placeholder="Rechercher un point ou une ville..." class="map-search-input">
            <button id="search-btn" class="map-search-btn">Rechercher</button>
            <div id="search-results" class="search-results"></div>
        </div>
    </div>

    <div class="map-container" id="map"></div>
    <?php include_once("INCLUDES/footer.php") ?>
</body>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([48.8566, 2.3522], 5);

    // Carte routière (OpenStreetMap)
    var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    });

    // Carte satellite (Esri World Imagery)
    var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.esri.com/">Esri</a>'
    });

    // Ajouter la carte routière par défaut
    osmLayer.addTo(map);

    // Contrôle pour basculer entre les cartes
    var baseMaps = {
        "Carte routière": osmLayer,
        "Satellite": satelliteLayer
    };

    L.control.layers(baseMaps).addTo(map);

    var geojsonData = <?= json_encode(json_decode($geojson)) ?>;

    function getIcon(url) {
        return L.icon({
            iconUrl: url ? 'INCLUDES/MAPSICONS/' + url : "INCLUDES/ICONS/default.png",
            iconSize: [30, 30], // Taille réduite
            iconAnchor: [15, 30],
            popupAnchor: [0, -30],
        });
    }



    var geoLayer = L.geoJSON(geojsonData, {
        pointToLayer: function(feature, latlng) {
            return L.marker(latlng, {
                icon: getIcon(feature.properties.icon)
            });
        },
        onEachFeature: function(feature, layer) {
            var url = feature.properties.description;

            var lat = layer.getLatLng().lat.toFixed(6);
            var lng = layer.getLatLng().lng.toFixed(6);
            var popupContent =
                "<div class='popup-content'>" +
                "<strong>" + feature.properties.name + "</strong><br>" +
                url +
                "<br><br>" +
                "<div class='popup-coordinates'>" +
                "<small>Lat: " + lat + " | Lng: " + lng + "</small>" +
                "</div>" +
                "</div>";
            layer.bindPopup(popupContent, {
                maxWidth: 300,
                className: 'custom-popup'
            });
        }
    }).addTo(map);

    if (geoLayer.getBounds().isValid()) {
        map.fitBounds(geoLayer.getBounds());
    }

    // Fonctionnalité de recherche
    var searchInput = document.getElementById('map-search');
    var searchBtn = document.getElementById('search-btn');
    var searchResults = document.getElementById('search-results');
    var markersGroup = L.layerGroup().addTo(map);

    // Rechercher dans les points de la carte
    function searchInMapPoints(query) {
        var results = [];
        var lowerQuery = query.toLowerCase();

        geojsonData.features.forEach(function(feature) {
            var name = feature.properties.name || '';
            var description = feature.properties.description || '';

            if (name.toLowerCase().includes(lowerQuery) ||
                description.toLowerCase().includes(lowerQuery)) {
                results.push({
                    type: 'point',
                    name: name,
                    description: description,
                    latlng: [feature.geometry.coordinates[1], feature.geometry.coordinates[0]],
                    feature: feature
                });
            }
        });

        return results;
    }

    // Rechercher une ville via Nominatim
    function searchCity(query) {
        return fetch('https://nominatim.openstreetmap.org/search?format=json&q=' +
                encodeURIComponent(query) + '&limit=5&addressdetails=1')
            .then(response => response.json())
            .then(data => {
                return data.map(item => ({
                    type: 'city',
                    name: item.display_name,
                    latlng: [parseFloat(item.lat), parseFloat(item.lon)],
                    city: item.address.city || item.address.town || item.address.village || ''
                }));
            })
            .catch(error => {
                console.error('Erreur de recherche:', error);
                return [];
            });
    }

    // Afficher les résultats
    function displayResults(results) {
        searchResults.innerHTML = '';

        if (results.length === 0) {
            searchResults.innerHTML = '<div class="search-result-item">Aucun résultat trouvé</div>';
            searchResults.classList.add('show');
            return;
        }

        results.forEach(function(result) {
            var item = document.createElement('div');
            item.className = 'search-result-item';
            item.innerHTML = '<div class="result-name">' + result.name + '</div>' +
                '<div class="result-type">' +
                (result.type === 'point' ? 'Point de la carte' : 'Ville') +
                '</div>';

            item.addEventListener('click', function() {
                map.setView(result.latlng, 13);

                // Si c'est un point, ouvrir le popup
                if (result.type === 'point' && result.feature) {
                    // Trouver le layer correspondant
                    geoLayer.eachLayer(function(layer) {
                        if (layer.feature && layer.feature === result.feature) {
                            layer.openPopup();
                        }
                    });
                } else {
                    // Marquer la ville trouvée
                    markersGroup.clearLayers();
                    L.marker(result.latlng)
                        .addTo(markersGroup)
                        .bindPopup('<strong>' + result.name + '</strong>')
                        .openPopup();
                }

                searchResults.classList.remove('show');
                searchInput.value = '';
            });

            searchResults.appendChild(item);
        });

        searchResults.classList.add('show');
    }

    // Effectuer la recherche
    function performSearch() {
        var query = searchInput.value.trim();

        if (query.length < 2) {
            searchResults.classList.remove('show');
            return;
        }

        // Rechercher dans les points de la carte
        var mapResults = searchInMapPoints(query);

        // Rechercher des villes
        searchCity(query).then(function(cityResults) {
            var allResults = mapResults.concat(cityResults);
            displayResults(allResults);
        });
    }

    // Variable pour éviter trop de requêtes
    var searchTimeout;

    // Effectuer la recherche automatiquement pendant la saisie
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        var query = searchInput.value.trim();

        if (query.length < 2) {
            searchResults.classList.remove('show');
            return;
        }

        // Délai de 300ms pour éviter trop de requêtes pendant la frappe
        searchTimeout = setTimeout(function() {
            performSearch();
        }, 300);
    });

    // Événements
    searchBtn.addEventListener('click', performSearch);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            clearTimeout(searchTimeout);
            performSearch();
        }
    });

    // Sélection au clavier (flèches haut/bas)
    var selectedIndex = -1;
    searchInput.addEventListener('keydown', function(e) {
        var items = searchResults.querySelectorAll('.search-result-item');

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
            updateSelection(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedIndex = Math.max(selectedIndex - 1, -1);
            updateSelection(items);
        } else if (e.key === 'Enter' && selectedIndex >= 0) {
            e.preventDefault();
            if (items[selectedIndex]) {
                items[selectedIndex].click();
            }
        }
    });

    function updateSelection(items) {
        items.forEach(function(item, index) {
            if (index === selectedIndex) {
                item.style.backgroundColor = '#1E3A8A';
            } else {
                item.style.backgroundColor = '';
            }
        });
    }

    // Fermer les résultats en cliquant ailleurs
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.map-search-container')) {
            searchResults.classList.remove('show');
            selectedIndex = -1;
        }
    });
</script>

</html>