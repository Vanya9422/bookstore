<?php

namespace App\Repository\Tasks;

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
