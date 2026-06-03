<?php
namespace App\Models;

class User {
    public function __construct(
        public readonly int $id,
        public readonly int $roleId,
        public readonly string $username,
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $avatarPath,
        public readonly string $createdAt,
        public readonly string $updatedAt,
        public readonly array $permissions = [],
    ) {}
}