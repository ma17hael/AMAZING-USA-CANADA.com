<?php
namespace App\Core;

class Response {
    public static function redirect(string $url, int $code = 302): never {
        http_response_code($code);
        header('Location: '  . $url);
        exit;
    }

    public static function redirectBack(string $fallback = '/'): never {
        $url = $_SERVER['HTTP_REFERER'] ?? $fallback;
        self::redirect($url);
    }

    public static function redirectWithLang(string $path, string $lang): never {
        self::redirect('/' . $lang . $path);
    }

    public static function notFound(): never {
        http_response_code(404);
        require_once __DIR__ . '/../../VIEWS/ERRORS/404.php';
        exit;
    }

    public static function forbidden(): never {
        http_response_code(403);
        require_once __DIR__ . '/../../VIEWS/ERRORS/403.php';
        exit;
    }

    public static function serverError(): never {
        http_response_code(500);
        require_once __DIR__ . '/../../VIEWS/ERRORS/500.php';
        exit;
    }

    public static function json(mixed $data, int $code = 200): never {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    public static function success(mixed $data = null, string $message = 'OK'): never {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public static function error(string $message, int $code = 400, mixed $data = null): never {
        self::json([
            'success' => false,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public static function setHeader(string $key, string $value): void {
        header($key . ': ' . $value);
    }

    public static function noCache(): void {
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
    }

    public static function download(string $filePath, string $fileName): never {
        if (!file_exists($filePath)) {
            self::notFound();
        }

        self::noCache();
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }
}