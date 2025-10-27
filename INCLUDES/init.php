<?php
session_start();

//Vérifie si une langue est passée en GET et valide
if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr','us','ca'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

//Par défaut, le site est en français
$lang = $_SESSION['lang'] ?? 'fr';

//Charge les traductions en conséquence
$translations = include __DIR__ . "/LANGUAGES/{$lang}.php";