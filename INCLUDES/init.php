<?php
// Empêche l'accès direct au fichier
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    http_response_code(403);
    exit("Accès interdit");
}

/**
 * Charge un fichier .env simple (KEY=VALUE) depuis la racine du projet.
 * Les variables déjà présentes dans l'environnement système ne sont pas écrasées.
 */
function loadProjectEnv(string $envPath): void
{
    if (!is_readable($envPath)) {
        return;
    }

    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $trimmed = trim($line);

        if ($trimmed === '' || str_starts_with($trimmed, '#')) {
            continue;
        }

        if (str_starts_with($trimmed, 'export ')) {
            $trimmed = trim(substr($trimmed, 7));
        }

        $parts = explode('=', $trimmed, 2);
        if (count($parts) !== 2) {
            continue;
        }

        $name = trim($parts[0]);
        $value = trim($parts[1]);

        if ($name === '' || preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $name) !== 1) {
            continue;
        }

        $isQuoted = (str_starts_with($value, '"') && str_ends_with($value, '"'))
            || (str_starts_with($value, "'") && str_ends_with($value, "'"));
        if ($isQuoted) {
            $value = substr($value, 1, -1);
        }

        if (getenv($name) !== false) {
            continue;
        }

        putenv("{$name}={$value}");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}

loadProjectEnv(dirname(__DIR__) . '/.env');

session_start();

//Vérifie si une langue est passée en GET et valide
if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr','us','ca'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

//Par défaut, le site est en français
$lang = $_SESSION['lang'] ?? 'fr';
$langBDD = 'fr';
if ($lang == 'us' || $lang == 'ca') {
    $langBDD = 'EN';
} else {
    $langBDD = 'FR';
}

//Charge les traductions en conséquence
$translations = include __DIR__ . "/LANGUAGES/{$lang}.php";
