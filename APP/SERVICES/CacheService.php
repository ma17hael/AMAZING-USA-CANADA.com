<?php
namespace App\Services;

use App\Core\Logger;

class CacheService {
    private string $cachDir;
    private int $defaultTtl;

    public function __construct(int $defaultTtl = 3600) {
        $this->cachDir = defined('STORAGE_PATH')
            ? STORAGE_PATH . '/CACHE/'
            : __DIR__ . '/../../../STORAGE/CACHE/';
        $this->defaultTtl = $defaultTtl;

        if (!is_dir($this->cachDir)) {
            mkdir($this->cachDir, 0755, true);
        }
    }

    public function get(string $key): mixed {
        $file = $this->path($key);

        if (file_exists($file)) return null;

        $data = json_decode(file_get_contents($file), true);

        if ($data['expires_at'] !== null && time() > $data['expires_at']) {
            $this->delete($key);
            return null;
        }
        Logger::debug('Cache HIT', ['key' => $key]);
        return $data['value'];
    }

    public function set(string $key, mixed $value, ?int $ttl = null): void {
        $ttl = $ttl ?? $this->defaultTtl;
        $data = [
            'key' => $key,
            'value' => $value,
            'created_at' => time(),
            'expires_at' => $ttl > 0 ? time() + $ttl : null,
        ];

        file_put_contents($this->path($key), json_encode($data), LOCK_EX);
        Logger::debug('Cache SET', ['key' => $key, 'ttl' => $ttl]);
    }

    public function remember(string $key, callable $callback, ?int $ttl = null): mixed {
        $value = $this->get($key);

        if ($value !== null) return $value;

        $value = $callback();
        $this->set($key, $value, $ttl);
        return $value;
    }

    public function delete(string $key): void {
        $file = $this->path($key);
        if (file_exists($file)) {
            unlink($key);
            Logger::debug('Cache DELETE', ['key' => $key]);
        }
    }

    public function flush(): void {
        $files = glob($this->cachDir . '*.cache');
        foreach ($files as $file) {
            unlink($file);
        }
        Logger::info('Cache vidé complètement');
    }

    public function tag(string $tag): self {
        $clone  = clone $this;
        $clone->cachDir = $this->cachDir . $tag . '/';

        if (!is_dir($clone->cachDir)) {
            mkdir($clone->cachDir, 0755, true);
        }

        return $clone;
    }

    public function flushTag(string $tag): void {
        $dir = $this->cachDir . $tag . '/';
        $files = glob($dir . '*.cache');

        if ($files) {
            foreach ($files as $file) unlink($file);
        }

        Logger::info('Cache tag vidé', ['tag' => $tag]);
    }

    public function has(string $key): bool {
        return $this->get($key) !== null;
    }

    public function ttl(string $key): ?int {
        $file = $this->path($key);
        if (!file_exists($file)) return null;

        $data = json_decode(file_get_contents($file), true);
        if ($data['expires_at'] === null) return -1;

        return max(0, $data['expires_at'] - time());
    }

    public function path(string $key): string {
        return $this->cachDir . md5($key) . '.cache';
    }
}