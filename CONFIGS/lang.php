<?php

function loadLang($lang = 'fr') {
    $basePath = __DIR__ . '/../LANGUAGES/';

    $baseLang = explode('-', $lang)[0];

    $defaultFile = $basePath . $baseLang . '.php';
    $specificFile = $basePath . $lang . '.php';

    $default = is_file($defaultFile) ? require $defaultFile : [];

    $specific = [];
    if (is_file($specificFile)) {
        $tmp = require $specificFile;
        $specific = is_array($tmp) ? $tmp : [];
    }

    return array_replace($default, $specific);
}

function t($key) {
    return $GLOBALS['L'][$key] ?? $key;
}

function getAvailableLanguages($db) {
    $stmt = $db->prepare("SELECT Name, LangCode FROM languages");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $basePath = __DIR__ . '/../LANGUAGES/';

    $available = [];

    foreach ($rows as $row) {

        $langCode = strtolower($row['LangCode']);

        $global = explode('-', $langCode)[0];

        $globalFile = $basePath . $global . '.php';
        $localFile  = $basePath . $langCode . '.php';

        // doit avoir AU MOINS le global
        if (is_file($globalFile)) {

            $available[] = [
                'code' => $langCode,
                'name' => $row['Name'],
                'has_local' => is_file($localFile)
            ];
        }
    }

    return $available;
}