<?php

namespace App\Repository\Client;

use App\Core\Contracts\PaginationInterface;
use App\Models\Author;
use App\Repository\BaseRepository;

class AuthorRepository extends BaseRepository implements AuthorRepositoryInterface {

    /**
     * @return string
     */
    protected function getModelClass(): string {
        return Author::class;
    }

    /**
     * @param int $perPage
     * @param int|null $currentPage
     * @return PaginationInterface
     */
    public function authorPaginate(int $perPage, ?int $currentPage = 1): PaginationInterface {
        return $this
            ->getModel()
            ->withCount('books', function () {
                return "(SELECT COUNT(*) FROM books WHERE books.author_id = authors.id)";
            })
            ->paginate($perPage, $currentPage);
    }
}