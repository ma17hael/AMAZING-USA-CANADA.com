<?php

function loadLang($lang = 'fr') {

    $basePath = __DIR__ . '/../LANGUAGES/';

    $lang = strtolower(str_replace('_', '-', $lang));

    $baseLang = explode('-', $lang)[0];

    $defaultFile = $basePath . $baseLang . '.php';
    $specificFile = $basePath . $lang . '.php';

    $default = [];
    $specific = [];

    if (is_file($defaultFile)) {
        $tmp = require $defaultFile;
        if (is_array($tmp)) {
            $default = $tmp;
        }
    }

    if (is_file($specificFile)) {
        $tmp = require $specificFile;
        if (is_array($tmp)) {
            $specific = $tmp;
        }
    }

    // merge SAFE (plus fiable que array_replace ici)
    return array_merge($default, $specific);
}

function t($key)
{
    return $GLOBALS['L'][$key] ?? $key;
}

function getAvailableLanguages($db)
{
    $stmt = $db->prepare("SELECT Name, LangCode, IconPath FROM languages");
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
                'flag' => $row['IconPath'] ?? null,
                'has_local' => is_file($localFile)
            ];
        }
    }

    return $available;
}
