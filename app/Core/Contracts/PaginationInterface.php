<?php

namespace App\Core\Contracts;

interface PaginationInterface
{
    public function getCurrentPage(): int;
    public function getTotalPages(): int;
    public function getPerPage(): int;
    public function getTotalItems(): int;
    public function getItems(): array;
}