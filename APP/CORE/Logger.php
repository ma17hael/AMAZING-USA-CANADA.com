<?php

namespace App\Core;

class Logger {
    private static string $logDir = __DIR__ . "/../../../LOGS/";

    public static function info(string $message, array $context = []): void {
        self::write('INFO', $message, $context);
    }

    public static function warning(string $message, array $context = []): void {
        self::write('WARNING', $message, $context);
    }

    public static function error(string $message, array $context = []): void {
        self::write('ERROR', $message, $context);
    }

    public static function debug(string $message, array $context = []): void {
        if (!self::isDebug()) return;
        self::write('DEBUG', $message, $context);
    }

    public static function sql(string $query, array $params = []): void {
        if (!self::isDebug()) return;
        self::write('SQL', $query, $params, 'sql');
    }

    public static function auth(string $message, array $context = []): void {
        self::write('AUTH', $message, $context, 'sql');
    }

    private static function write(string $level, string $message, array $context = [], string $file = 'app'): void {
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0755, true);
        }

        $date = date('Y-m-d');
        $time = date('H:i:s');
        $logFile = self::$logDir . $file . '-' . $date . '.log';

        $contextStr = empty($context) ? '' : ' | ' . json_encode($context, JSON_UNESCAPED_UNICODE);
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
        $line = "[{$time}] [{$level}] [{$ip}] {$message}{$contextStr}" . PHP_EOL;

        file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
    }

    private static function isDebug(): bool {
        return defined("APP_DEBUG") && APP_DEBUG === true;
    }
}