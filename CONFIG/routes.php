<?php

use App\Core\Router;

$router = new Router();

//Pages Publiques
$router->get('/', [App\Controllers\HomeController::class, 'index']);

return $router;