<?php

namespace App\Repository\Contracts;

/**
 * Общий интерфейс для всех репозиториев
 */
interface RepositoryInterface {


    /**
     * Пагинация результатов запроса.
     *
     * @param int $perPage Количество результатов на страницу.
     * @param ?int $currentPage Номер текущей страницы, по умолчанию 1.
     * @param array $relations Массив связей, которые нужно подгрузить с результатами.
     *
     * @return array
     */
    public function paginate(int $perPage, ?int $currentPage = 1, array $relations = []): array;
}