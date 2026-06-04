<?php
namespace App\Core;

use App\Core\Database;

class Validator {
    private array $errors = [];
    private array $data = [];

    public function validate(array $data, array $rules): self {
        $this->data = $data;
        $this->errors = [];

        foreach ($rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            foreach ($rules as $rule) {
                $this->applyRule($field, $rule);
            }
        }

        return $this;
    }

    public function fails(): bool {
        return !empty($this->errors);
    }

    public function passes(): bool {
        return empty($this->errors);
    }

    public function errors(): array {
        return $this->errors;
    }

    public function firstError(string $field): ?string {
        return $this->errors[$field][0] ?? null;
    }

    public function applyRule(string $field, string $rule): void {
        [$ruleName, $param] = array_pad(explode(':', $rule, 2), 2, null);

        $value = $this->data[$field] ?? null;

        match($ruleName) {
            'required' => $this->validateRequired($field, $value),
            'email' => $this->validateEmail($field, $value),
            'min' => $this->validateMin($field, $value, (int) $param),
            'max' => $this->validateMax($field, $value, (int) $param),
            'numeric' => $this->validateNumeric($field, $value),
            'alpha' => $this->validateAlpha($field, $value),
            'confirmed' => $this->validateConfirmed($field, $value),
            'unique' => $this->validateUnique($field, $value, $param),
            'exists' => $this->validateExists($field, $value, $param),
            'in' => $this->validateIn($field, $value, $param),
            'url' => $this->validateUrl($field, $value),
            'image' => $this->validateImage($field, $value),
            'maxsize' => $this->validateMaxSize($field, $value, (int) $param),
            default => null,
        };
    }

    private function validateRequired(string $field, mixed $value): void {
        if ($value === null || trim((string) $value) === '') {
            $this->addError($field, 'Le champ ' . $field . ' est obligatoire.');
        }
    }

    private function validateEmail(string $field, mixed $value): void {
        if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, 'Le champ ' . $field . ' doit être un email valide.');
        }
    }

    private function validateMin(string $field, mixed $value, int $min): void {
        if ($value && mb_strlen((string) $value) < $min) {
            $this->addError($field, 'Le champ ' . $field . ' doit contenir au moins ' . $min . ' caractères.');
        }
    }

    private function validateMax(string $field, mixed $value, int $max): void {
        if ($value && mb_strlen((string) $value) > $max) {
            $this->addError($field, 'Le champ ' . $field . ' ne doit pas dépasser ' . $max . ' caractères.');
        }
    }

    private function validateNumeric(string $field, mixed $value): void {
        if ($value && !is_numeric($value)) {
            $this->addError($field, 'Le champ ' . $field . ' doit être un nombre.');
        }
    }

    private function validateAlpha(string $field, mixed $value): void {
        if ($value && !preg_match('/^[\p{L}\p{N}_-]+$/u', (string) $value)) {
            $this->addError($field, 'Le champ ' . $field . ' ne doit contenir que des lettres, chiffres et symboles tel que - et _.');
        }
    }

    private function validateConfirmed(string $field, mixed $value): void {
        $confirmation = $this->data[$field . '_confirmation'] ?? null;
        if ($value != $confirmation) {
            $this->addError($field, 'Le champ ' . $field . ' ne correspond pas à sa confirmation.');
        }
    }

    private function validateUnique(string $field, mixed $value, string $param): void {
        [$table, $column] = array_pad(explode(',', $param), 2, $field);

        if (!$value) return;

        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT COUNT(*) FROM `{$table}` WHERE `{$column}` = ?");
        $stmt->execute([$value]);

        if ((int) $stmt->fetchColumn() > 0) {
            $this->addError($field, 'Cette valeur est déja utilisé pour ' . $field . '.');
        }
    }

    private function validateExists(string $field, mixed $value, string $param): void {
        [$table, $column] = array_pad(explode(',', $param), 2, $field);

        if (!$value) return;

        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT COUNT(*) FROM `{$table}` WHERE `{$column}` = ?");
        $stmt->execute([$value]);

        if ((int) $stmt->fetchColumn() === 0) {
            $this->addError($field, 'La valeur du champ ' . $field . ' n\'existe pas.');
        }
    }

    private function validateIn(string $field, mixed $value, string $param): void {
        $allowed = explode(',', $param);
        if ($value && !in_array($value, $allowed)) {
            $this->addError($field, 'La valeur du champ ' . $field . ' n\'est pas autorisée.');
        }
    }

    private function validateUrl(string $field, mixed $value): void {
        if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
            $this->addError($field, 'Le champ ' . $field . ' doit être une URL valide.');
        }
    }

    private function validateImage(string $field, mixed $value): void {
        if (!$value || !isset($value['tmp_name'])) return;

        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $mime = mime_content_type($value['tmp_name']);

        if (!in_array($mime, $allowed)) {
            $this->addError($field, 'Le fichier doit être une image (JPG, PNG, WEBP, GIF).');
        }
    }

    private function validateMaxSize(string $field, mixed $value, int $maxKb): void {
        if (!$value || !isset($value['size'])) return;

        if ($value['size'] > $maxKb * 1024) {
            $this->addError($field, 'Le fichier ne doit pas dépasser ' . $maxKb . ' Ko.');
        }
    }

    private function addError(string $field, string $message): void {
        $this->errors[$field][] = $message;
    }
}