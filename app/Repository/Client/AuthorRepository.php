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

    /**
     * @param int $perPage
     * @param int|null $currentPage
     * @return array
     */
    public function authorPaginate(int $perPage, ?int $currentPage = 1): array {
        return $this
            ->getModel()
            ->withCount('books', function () {
                return "(SELECT COUNT(*) FROM books WHERE books.author_id = authors.id)";
            })
            ->paginate($perPage, $currentPage);
    }
}