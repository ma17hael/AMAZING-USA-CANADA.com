<?php
require_once __DIR__ . '/../GLOBAL-INCLUDES/CONFIGS/bootstrap.php';

var_dump(function_exists('apcu_fetch'));
?>
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AMAZING USA CANADA - Accueil</title>
    <!-- GLOBAL CSS -->
    <link rel="stylesheet" href="http://assets.amazing-usa-canada.local/CSS/global.css">
    <!-- HEADER CSS -->
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/index.css">
</head>
<body>
    <?php include_once('LAYOUT/header.php');?>
</body>
<script src="http://assets.amazing-usa-canada.local/JS/global.js"></script>