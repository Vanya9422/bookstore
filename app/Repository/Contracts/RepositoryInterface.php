<?php

namespace App\Repository\Contracts;

use App\Core\Contracts\PaginationInterface;
use App\Core\Database\Model;

/**
 * Общий интерфейс для всех репозиториев
 */
interface RepositoryInterface
{

    /**
     * Создает новую запись в базе данных.
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;

    /**
     * Обновляет запись в базе данных по заданному идентификатору.
     *
     * @param int $id
     * @param array $attributes
     * @return mixed
     */
    public function update(int $id, array $attributes): mixed;

    /**
     * Удаляет запись из базы данных по идентификатору.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Находит запись по идентификатору, опционально загружая связи.
     * @param int $id
     * @param array $relations
     * @return ?Model
     */
    public function find(int $id, array $relations = []): ?Model;

    /**
     * Пагинация результатов запроса.
     *
     * @param int $perPage Количество результатов на страницу.
     * @param ?int $currentPage Номер текущей страницы, по умолчанию 1.
     * @param array $relations Массив связей, которые нужно подгрузить с результатами.
     *
     * @return PaginationInterface
     */
    public function paginate(int $perPage, ?int $currentPage = 1, array $relations = []): PaginationInterface;

    /**
     * Получает все записи.
     *
     * @return array
     */
    public function all(): array;
}