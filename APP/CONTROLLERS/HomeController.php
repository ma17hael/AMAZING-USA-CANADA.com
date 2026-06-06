<?php
namespace App\Controllers;

use App\Services\TranslationService;

class HomeController {
    public function index(array $params, $translator, $router, $auth, $request): void {
        require_once APP_VIEWS_PATH . '/HOME/index.php';
    }
}