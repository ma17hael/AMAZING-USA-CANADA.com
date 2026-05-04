<?php

function loadLang($lang = 'fr-FR')
{
    $basePath = __DIR__ . '/../LANGUAGES/';

    $lang = strtolower(str_replace('_', '-', $lang));
    $base = explode('-', $lang)[0];

    $files = [
        $basePath . $base . '.php',
        $basePath . $lang . '.php'
    ];

    $result = [];

    foreach ($files as $file) {
        if (is_file($file)) {
            $data = require $file;
            if (is_array($data)) {
                $result = array_merge($result, $data);
            }
        }
    }

    return $result;
}

function t($key)
{
    return $GLOBALS['L'][$key] ?? $key;
}

function getAvailableLanguages($db)
{
    $stmt = $db->prepare("
        SELECT 
            Name,
            LangCode,
            IconPath,
            FallbackID
        FROM languages
    ");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $basePath = __DIR__ . '/../LANGUAGES/';

    $available = [];

    foreach ($rows as $row) {

        $langCode = strtolower($row['LangCode']);
        $base = explode('-', $langCode)[0];

        $available[] = [
            'code' => $langCode,
            'name' => $row['Name'],
            'flag' => $row['IconPath'],
            'fallbackID' => $row['FallbackID'],

            // existence réelle des fichiers
            'files' => [
                'base' => is_file($basePath . $base . '.php'),
                'specific' => is_file($basePath . $langCode . '.php')
            ]
        ];
    }

    return $available;
}
