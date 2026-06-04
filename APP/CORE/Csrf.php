<?php
namespace App\Core;

class Csrf {
    private const TOKEN_KEY = 'csrf_token';
    private const TOKEN_LENGTH = 64;

    public static function generate(): string {
        if (empty($_SESSION[self::TOKEN_KEY])) {
            $_SESSION[self::TOKEN_KEY] = bin2hex(
                random_bytes(self::TOKEN_LENGTH / 2)
            );
        }
        return $_SESSION[self::TOKEN_KEY];
    }

    public static function regenerate(): string {
        $_SESSION[self::TOKEN_KEY] = bin2hex(
            random_bytes(self::TOKEN_LENGTH / 2)
        );
        return $_SESSION[self::TOKEN_KEY];
    }

    public static function validate(?string $token): bool {
        if (empty($token) || empty($_SESSION[self::TOKEN_KEY])) {
            Logger::warning('CSRF - Token manquant', [
                'uri' => $_SERVER['REQUEST_URI'],
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
            ]);
            return false;
        }
        $valid = hash_equals($_SESSION[self::TOKEN_KEY], $token);

        if (!$valid) {
            Logger::warning('CSRF - Token invalide', [
                'uri' => $_SERVER['REQUEST_URI'],
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
            ]);
        }

        return $valid;
    }

    public static function validateOrFail(?string $token): void {
        if (!self::validate($token)) {
            Response::forbidden();
        }
    }

    public static function field(): string {
        return '<input type="hidden" name="csrf_token" value="'
            . self::generate()
            . '">';
    }

    public static function token(): string {
        return self::generate();
    }
}