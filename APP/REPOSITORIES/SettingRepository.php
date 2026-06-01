<?php
namespace App\Repositories;

use App\Models\Setting;
use App\Core\Database;

class SettingRepository {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findByKey(string $key): ?Setting {
        $stmt = $this->db->prepare(
            "SELECT * FROM settings WHERE SettingKey = :key"
        );
        $stmt->bindParam("key", $key, \PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row
            ? new Setting($row['SettingKey'], $row['SettingType'], $row['SettingValue'])
            : null;
    }

    public function findAll(): array {
        $stmt = $this->db->query('SELECT * FROM settings');
        return array_map(
            fn($row) => new Setting($row['SettingKey'], $row['SettingType'], $row['SettingValue']),
            $stmt->fetchAll()
        );
    }
}