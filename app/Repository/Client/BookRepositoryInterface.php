<?php

namespace App\Repository\Client;

use App\Core\Contracts\PaginationInterface;

interface BookRepositoryInterface {

    /**
     * @param int $perPage
     * @param int|null $currentPage
     * @return PaginationInterface
     */
    public function bookPaginate(int $perPage, ?int $currentPage = 1): PaginationInterface;
}
