<?php

namespace App\Repository\Client;

use App\Core\Contracts\PaginationInterface;
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
     * @return PaginationInterface
     */
    public function bookPaginate(int $perPage, ?int $currentPage = 1): PaginationInterface {
        return $this
            ->getModel()
            ->select(['books.*', 'authors.name'])
            ->join('authors', 'books.author_id', '=', 'authors.id')
            ->paginate($perPage, $currentPage);
    }
}