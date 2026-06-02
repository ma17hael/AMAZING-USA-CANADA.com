<?php

namespace App\Models;

class Language {
    public function __construct(
        public readonly int $id,
        public readonly string $langCode,
        public readonly string $name,
        public readonly string $localeCode,
    ){}
}