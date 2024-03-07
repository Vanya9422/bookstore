<?php

namespace App\Repository\Client;

use App\Models\Book;
use App\Repository\BaseRepository;

class BookRepository extends BaseRepository implements UserRepositoryInterface {

    /**
     * @return string
     */
    protected function getModelClass(): string {
        return Book::class;
    }
}