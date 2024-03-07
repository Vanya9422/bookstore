<?php

namespace App\Models;



use App\Core\Database\Model;
use App\Enums\UserRole;

/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property Role $role
 */
class User extends Model {

    public function getPassword(): string {
        return $this->password;
    }

    /**
     * Проверяет, является ли пользователь администратором.
     */
    public function isAdmin(): bool {
        return $this->role->name === UserRole::Admin->value;
    }

    public function role(): Role {
        return $this->belongsTo(Role::class, 'role_id');
    }
}