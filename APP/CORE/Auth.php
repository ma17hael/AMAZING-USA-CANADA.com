<?php
namespace App\Core;

use App\Models\User;
use App\Repositories\UserRepository;

class Auth {
    private static ?User $currentUser = null;
    private UserRepository $userRepo;

    public function __construct() {
        $this->userRepo = new UserRepository();
    }

    public function login(string $email, string $password): bool {
        $user = $this->userRepo->findByEmail($email);

        if (!$user || !password_verify($password, $user->password)) {
            Logger::auth("Tentative de connexion échouée", ['email' => $email]);
            return false;
        }

        self::$currentUser = $user;
        $_SESSION['user_id'] = $user->id;

        Logger::auth('Connexion réussi', [
            'userID' => $user->id,
            'username' => $user->username,
        ]);

        return true;
    }

    public function logout(): void {
        Logger::auth('Déconnexion', ['userID' => $_SESSION['user_id'] ?? null]);
        self::$currentUser = null;
        $_SESSION = [];
        session_destroy();
    }

    public function user(): ?User {
        if (self::$currentUser !== null) {
            return self::$currentUser;
        }

        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        self::$currentUser = $this->userRepo->findById($_SESSION['user_id']);
        return self::$currentUser;
    }

    public function check(): bool {
        return self::user() !== null;
    }

    public function guest(): bool {
        return !$this->check();
    }

    public function can(string $permission): bool {
        $user = $this->user();
        if (!$user) return false;

        return in_array($permission, $user->permissions);
    }

    public function canAny(array $permissions): bool {
        foreach ($permissions as $permission) {
            if ($this->can($permission)) return true;
        }
        return false;
    }

    public function canAll(array $permissions): bool {
        foreach ($permissions as $permission) {
            if (!$this->can($permission)) return false;
        }
        return true;
    }

    public function requireLogin(string $redirectTo = '/connexion'): void {
        if ($this->guest()) {
            Logger::warning('Accès refusé - non connecté', [
                'uri' => $_SERVER['REQUEST_URI'],
            ]);
            Response::redirect($redirectTo);
        }
    }

    public function requirePermission(string $permission, string $redirectTo = '/'): void {
        $this->requireLogin();

        if (!$this->can($permission)) {
            Logger::warning('Accès refusé - permission manquante', [
                'userID' => $this->user()->id,
                'permission' => $permission,
                'uri' => $_SERVER['REQUEST_URI'],
            ]);
            Response::redirect($redirectTo);
        }
    }

    public function requireAdmin(): void {
        $this->requirePermission(Permission::ACCESS_ADMIN_PANEL);
    }

    public function hasMapAccess(int $mapId): bool {
        $user = $this->user();
        if (!$user) return false;

        if ($this->requirePermission(Permission::ACCESS_ALL_MAPS)) return true;

        return $this->userRepo->hasMapAccess($user->id, $mapId);
    }
}