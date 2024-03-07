<?php

namespace App\Repository\Client;

use App\Models\Book;
use App\Repository\BaseRepository;

class BookRepository extends BaseRepository implements BookRepositoryInterface {

    /**
     * @return string
     */
    protected function getModelClass(): string {
        return Book::class;
    }

    /**
     * @param int $perPage
     * @param int|null $currentPage
     * @return array
     */
    public function bookPaginate(int $perPage, ?int $currentPage = 1): array {
        return $this
            ->getModel()
            ->join('authors', 'books.author_id', '=', 'authors.id')
            ->paginate($perPage, $currentPage);
    }
}