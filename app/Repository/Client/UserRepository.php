<?php

namespace App\Repository\Client;

use App\Core\Database\Model;
use App\Models\User;
use App\Repository\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface {

    /**
     * @return string
     */
    protected function getModelClass(): string {
        return User::class;
    }

    /**
     * Находит пользователя по его email.
     *
     * @param string $email
     * @param array $relations
     * @return Model|null
     */
    public function findByEmail(string $email, array $relations = []): ?Model {
        return $this->getModel()
            ->with($relations)
            ->where('email','=', $email)
            ->first();
    }
}