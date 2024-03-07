<?php

namespace App\Repository\Client;

use App\Models\Author;
use App\Repository\BaseRepository;

class AuthorRepository extends BaseRepository implements AuthorRepositoryInterface {

    /**
     * @return string
     */
    protected function getModelClass(): string {
        return Author::class;
    }
}