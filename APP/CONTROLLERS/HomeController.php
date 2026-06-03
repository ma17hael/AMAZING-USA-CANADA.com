<?php
namespace App\Controllers;

use App\Services\TranslationService;

class HomeController {
    public function index(array $params, $translator, $router, $auth): void {
        require_once __DIR__ . '/../../VIEWS/HOME/index.php';
    }
}