<?php

namespace App\Repository\Client;

use App\Core\Database\Model;

interface UserRepositoryInterface {

    /**
     * Находит пользователя по его email.
     *
     * @param string $email
     * @param array $relations
     * @return Model|null
     */
    public function findByEmail(string $email, array $relations): ?Model;
}