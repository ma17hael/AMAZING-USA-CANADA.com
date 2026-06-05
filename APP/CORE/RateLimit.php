<?php
namespace App\Core;

class RateLimit {
    private string $storageDir;

    public function __construct() {
        $this->storageDir = __DIR__ . "/../../../STORAGE/RATELIMIT/";

        if (!is_dir($this->storageDir)) {
            mkdir($this->storageDir, 0755, true);
        }
    }

    public function check(string $action, int $maxAttempts, int $decaySeconds, ?string $identifier = null): bool {
        $identifier = $identifier ?? $this->getIp();
        $key = $this->buildKey($action, $identifier);
        $data = $this->read($key);

        $now = time();
        $data = array_filter($data, fn($timestamp) => ($now - $timestamp) < $decaySeconds);

        if (count($data) >= $maxAttempts) {
            Logger::warning('RateLimit dépassé', [
                'action' => $action,
                'identifier' => $identifier,
                'attempts' => count($data),
                'max' => $maxAttempts,
            ]);
            return false;
        }

        $data[] = $now;
        $this->write($key, array_values($data));

        return true;
    }

    public function checkOrFail(string $action, int $maxAttempts, int $decaySeconds, ?string $identifier = null): void {
        if (!$this->check($action, $maxAttempts, $decaySeconds, $identifier)) {
            $this->tooManyAttempts();
        }
    }

    public function remainingAttempts(string $action, int $maxAttempts, int $decaySeconds, ?string $identifier = null): int {
        $identifier = $identifier ?? $this->getIp();
        $key = $this->buildKey($action, $identifier);
        $data = $this->read($key);

        $now = time();
        $data = array_filter($data, fn($timestamp) => ($now - $timestamp) < $decaySeconds);
        
        return max(0, $maxAttempts - count($data));
    }

    public function retryAfter(string $action, int $decaySeconds, ?string $identifier = null): int {
        $identifier = $identifier ?? $this->getIp();
        $key = $this->buildKey($action, $identifier);
        $data = $this->read($key);

        if (empty($data)) return 0;

        $oldest = min($data);
        return max(0, $decaySeconds - (time() - $oldest));
    }

    public function reset(string $action, ?string $identifier = null): void {
        $identifier = $identifier ?? $this->getIp();
        $key = $this->buildKey($action, $identifier);
        $file = $this->storageDir . $key . '.json';

        if (file_exists($file)) {
            unlink($file);
        }
    }

    private function tooManyAttempts(): never {
        http_response_code(429);
        header('Retry-After: 60');

        if (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) {
            Response::error('Trop de tentative. Réessayez plus tard.', 429);
        }

        require_once __DIR__ . '/../../VIEWS/ERRORS/429.php';
        exit;
    }

    private function read(string $key): array {
        $file = $this->storageDir . $key . '.json';
        if (!file_exists($file)) return [];

        $content = file_get_contents($file);
        return json_decode($content, true) ?? [];
    }

    private function write(string $key, array $data): void {
        $file = $this->storageDir . $key . '.json';
        file_put_contents($file, json_encode($data), LOCK_EX);
    }

    private function buildKey(string $action, string $identifier): string {
        return preg_replace('/[^a-zA-Z0-9_-]/', '_', $action . '_' . $identifier);
    }

    private function getIp(): string {
        return $_SERVER['HTTP_X_FORWARDED_FOR']
            ?? $_SERVER['HTTP_CLIENT_IP']
            ?? $_SERVER['REMOTE_ADDR']
            ?? 'Unknown';
    }
}