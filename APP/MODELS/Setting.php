<?php
namespace App\Models;

class Setting {
    public function __construct(
        public readonly string $key,
        public readonly string $type,
        public readonly string $rawValue,
    ) {}

    public function getValue(): mixed {
        return match ($this->type) {
            'INT' => (int) $this->rawValue,
            'FLOAT' => (float) $this->rawValue,
            'BOOL' => (bool) $this->rawValue,
            'JSON' => json_decode($this->rawValue, true),
            default => $this->rawValue,
        };
    }
}