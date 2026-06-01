<?php

namespace App\Services;

class MaintenanceService {
    private bool $isMaintenance;
    private string $maintenanceUrl;
    private array $allowedIps;

    public function __construct(SettingService $settings) {
        $this->isMaintenance = $settings->get("MAINTENANCE_MODE", false);
        $this->allowedIps = $settings->get("MAINTENANCE_ALLOWED_IPS", "[]");
        $this->maintenanceUrl = $settings->get("MAINTENANCE_URL", "");
    }

    public function check(): void {
        if (!$this->isMaintenance) return;
        if ($this->isAllowedIps()) return;

        http_response_code(503);
        header("Retry-After: 3600");
        header("Location: " . $this->maintenanceUrl);
        exit;
    }

    private function isAllowedIps(): bool {
        return in_array($this->getClientIp(), $this->allowedIps);
    }

    private function getClientIp(): string {
        return $_SERVER['HTTP_X_FORWARDED_FOR']
            ?? $_SERVER['HTTP_CLIENT_IP']
            ?? $_SERVER['REMOTE_ADDR']
            ?? '';
    }
}