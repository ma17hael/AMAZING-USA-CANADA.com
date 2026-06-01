<?php

namespace App\Services;

use App\Repositories\SettingRepository;

class SettingService {
    private SettingRepository $repo;
    private array $cache = [];

    public function __construct() {
        $this->repo = new SettingRepository();
    }

    public function get(string $key, mixed $default = null): mixed {
        if (!isset($this->cache[$key])) {
            $setting = $this->repo->findByKey($key);
            if (!$setting) return $default;
            $this->cache[$key] = $setting;
        }
        return $this->cache[$key]->getValue();
    }

    public function preloadAll(): void {
        foreach ($this->repo->findAll() as $setting) {
            $this->cache[$setting->key] = $setting;
        }
    }
}