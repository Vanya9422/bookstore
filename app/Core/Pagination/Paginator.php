<?php

namespace App\Core\Pagination;

use App\Core\Contracts\PaginationInterface;

class Paginator implements PaginationInterface {

    protected int $currentPage;
    protected int $totalPages;

    protected int $perPage;
    protected int $totalItems;
    protected array $items;

    public function __construct(array $paginationData) {
        $this->currentPage = $paginationData['current_page'] ?? 1;
        $this->totalPages = $paginationData['total_pages'] ?? 1;
        $this->perPage = $paginationData['per_page'] ?? 10;
        $this->totalItems = $paginationData['total'] ?? 0;
        $this->items = $paginationData['data'] ?? [];
    }

    public function getCurrentPage(): int {
        return $this->currentPage;
    }

    public function getTotalPages(): int {
        return $this->totalPages;
    }

    public function getPerPage(): int {
        return $this->perPage;
    }

    public function getTotalItems(): int {
        return $this->totalItems;
    }

    public function getItems(): array {
        return $this->items;
    }
}