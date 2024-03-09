<?php

namespace App\Repository\Client;

use App\Core\Contracts\PaginationInterface;

interface AuthorRepositoryInterface {

    /**
     * @param int $perPage
     * @param int|null $currentPage
     * @return PaginationInterface
     */
    public function authorPaginate(int $perPage, ?int $currentPage = 1): PaginationInterface;
}
