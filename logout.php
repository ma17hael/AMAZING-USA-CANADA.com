<?php
include_once("INCLUDES/init.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_unset();
    session_destroy();
}

header('Location: index.php');
exit;
