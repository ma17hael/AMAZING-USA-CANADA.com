<?php
namespace App\Repositories;

use App\Models\User;
use App\Core\Database;

class UserRepository {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findById(int $id): ?User {
        $stmt = $this->db->prepare('
            SELECT u.*, GROUP_CONCAT(p.PermissionCode) as permissions
            FROM users u
            LEFT JOIN role_permissions rp ON rp.RoleID = u.RoleID
            LEFT JOIN permissions p ON p.PermissionID = rp.PermissionID
            WHERE u.UserID = :id
            GROUP BY u.UserID
        ');
        $stmt->bindParam('id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    public function findByEmail(string $email): ?User {
        $stmt = $this->db->prepare('
            SELECT u.*, GROUP_CONCAT(p.PermissionCode) as permissions
            FROM users u
            LEFT JOIN role_permissions rp ON rp.RoleID = u.RoleID
            LEFT JOIN permissions p ON p.PermissionID = rp.PermissionID
            WHERE u.Email = :mail
            GROUP BY u.UserID
        ');
        $stmt->bindParam('email', $email, \PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    public function hasMapAccess(int $userId, int $mapId): bool {
        $stmt = $this->db->prepare('
            SELECT COUNT(*) FROM user_map_access
            WHERE UserID = :userid AND MapID = :mapid
        ');
        $stmt->bindParam('userid', $userId, \PDO::PARAM_INT);
        $stmt->bindParam('mapid', $mapId, \PDO::PARAM_INT);
        $stmt->execute();
        return (bool) $stmt->fetchColumn();
    }

    private function hydrate(array $row) {
        $permissions = $row['permissions']
            ? explode(',', $row['permissions'])
            : [];
        
        return new User(
            id: $row['UserID'],
            roleId: $row['RoleID'],
            username: $row['Username'],
            email: $row['Email'],
            password: $row['Password'],
            avatarPath: $row['AvatarPath'],
            createdAt: $row['CreatedAt'],
            updatedAt : $row['UpdatedAt'],
            permissions: $permissions,
        );
    }
}