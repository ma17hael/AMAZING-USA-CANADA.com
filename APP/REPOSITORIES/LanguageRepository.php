<?php

namespace App\Repositories;

use App\Models\Language;
use App\Core\Database;

class LanguageRepository {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findAll(): array {
        $stmt = $this->db->query("SELECT * FROM languages");
        return array_map(
            fn($row) => new Language($row['LanguageID'],$row['LangCode'],$row['Name'],$row['LocaleCode']),
            $stmt->fetchAll()
        );
    }

    public function findByCode(string $code): ?Language {
        $stmt = $this->db->prepare('SELECT * FROM languages WHERE LangCode = :lang');
        $stmt->bindParam('lang', $code, \PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row
            ? new Language($row['LanguageID'], $row['LangCode'], $row['Name'], $row['LocaleCode'])
            : null;
    }
}