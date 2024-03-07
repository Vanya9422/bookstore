<?php

namespace App\Models;



use App\Core\Database\Model;

class User extends Model {

    public static string $table = 'users';

    public function role() {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function getPassword() {
        return $this->password;
    }
}