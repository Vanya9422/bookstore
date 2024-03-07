<?php

namespace App\Repository\Client;

interface BookRepositoryInterface {

    /**
     * @param int $perPage
     * @param int|null $currentPage
     * @return array
     */
    public function bookPaginate(int $perPage, ?int $currentPage = 1): array;
}
