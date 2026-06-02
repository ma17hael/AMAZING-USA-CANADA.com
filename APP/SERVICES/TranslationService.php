<?php

namespace App\Services;

use App\Repositories\LanguageRepository;

class TranslationService {
    private string $currentLang;
    private array $translations = [];
    private array $fallback = [];
    private string $defaultLang = 'fr';

    public function __construct(private LanguageRepository $langRepo = new LanguageRepository()) {
        $this->loadFallback();
    }

    public function detect(?string $urlLang = null, ?string $userLang = null, ?string $sessionLang = null): void {
        $lang = $urlLang ?? $userLang ?? $sessionLang ?? $this->fromBrowser() ?? $this->defaultLang;
        $this->setLang($lang);
    }

    public function setLang(string $code): void {
        $language = $this->langRepo->findByCode($code);
        $this->currentLang = $language ? $code : $this->defaultLang;

        $this->loadTranslations($this->currentLang);

        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['lang'] = $this->currentLang;
        }
    }

    public function get(string $key, array $params = []): string {
        $text = $this->translations[$key]
            ?? $this->fallback[$key]
            ?? $key;
        
        foreach ($params as $k => $v) {
            $text = str_replace(':' . $k, $v, $text);
        }
        return $text;
    }

    public function getLang(): string {
        return $this->currentLang;
    }

    public function getAvailable(): array {
        return $this->langRepo->findAll();
    }

    private function loadTranslations(string $code): void {
        $base = explode('-', $code)[0];
        $baseFile = __DIR__ . '/../../LANGUAGES/' . $base . '.php';
        $base_translations = file_exists($baseFile) ? require $baseFile : [];

        $variantFile = __DIR__ . '/../../LANGUAGES/' . $code . '.php';
        $variant_translations = file_exists($variantFile) ? require $variantFile : [];

        $this->translations = array_merge($base_translations, $variant_translations);
    }

    private function loadFallback(): void {
        $file = __DIR__ . '/../LANGUAGES/' . $this->defaultLang . '.php';
        $this->fallback = file_exists($file) ? require $file : [];
    }

    private function fromBrowser(): ?string {
        $accepted = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
        preg_match('/([a-z]{2}(?:-[A-Z]{2})?)/i', $accepted, $matches);
        $code = strtolower($matches[1] ?? '');

        $supported = ['fr', 'fr-ca', 'en', 'en-ca'];
        return in_array($code, $supported) ? $code : null;
    }
}