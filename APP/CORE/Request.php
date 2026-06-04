<?php
namespace App\Core;

class Request {
    public function get(string $key, mixed $default = null): mixed {
        return isset($_GET[$key])
            ? $this->clean($_GET[$key])
            : $default;
    }

    public function allGet(): array {
        return array_map([$this, 'clean'], $_GET);
    }

    public function post(string $key, mixed $default = null): mixed {
        return isset($_POST[$key])
            ? $this->clean($_POST[$key])
            : $default;
    }

    public function allPost(): array {
        return array_map([$this, 'clean'], $_POST);
    }

    public function file(string $key): ?array {
        if (!isset($_FILES[$key]) || $_FILES[$key]['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        return $_FILES[$key];
    }

    public function hasFile(string $key): bool {
        return isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK;
    }

    public function method(): string {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function isGet(): bool {
        return $this->method() === 'GET';
    }

    public function isPost(): bool {
        return $this->method() === 'POST';
    }

    public function isAjax(): bool {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }

    public function isJson(): bool {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        return str_contains($contentType, 'application/json');
    }

    public function uri(): string {
        return $_SERVER['REQUEST_URI'] ?? '/';
    }

    public function path(): string {
        return parse_url($this->uri(), PHP_URL_PATH) ?? '/';
    }

    public function ip(): string {
        return $_SERVER['HTTP_X_FORWARDED_FOR']
            ?? $_SERVER['HTTP_CLIENT_IP']
            ?? $_SERVER['REMOTE_ADDR']
            ?? 'Unknown';
    }

    public function userAgent(): string {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    }

    public function referer(): ?string {
        return $_SERVER['HTTP_REFERER'] ?? null;
    }

    public function json(): array {
        if (!$this->isJson()) return [];

        $body = file_get_contents('php://input');
        return json_decode($body, true) ?? [];
    }

    public function jsonKey(string $key, mixed $default = null): mixed {
        return $this->json()[$key] ?? $default;
    }

    private function clean(mixed $value): mixed {
        if (is_array($value)) {
            return array_map([$this, 'clean'], $value);
        }
        return htmlspecialchars(trim((string) $value), ENT_QUOTES, 'UTF-8');
    }
}