<?php

namespace App\Core\Contracts;

use App\Core\Database\Model;

interface ModelInterface
{
    /**
     * Найти и вернуть запись по идентификатору.
     *
     * @param mixed $id Идентификатор записи.
     * @return mixed Найденная запись или null, если запись не найдена.
     */
    public function find($id);

    /**
     * Получает первую запись, соответствующую условиям выборки.
     *
     * @return ?Model
     */
    public function first(): ?Model;

    /**
     * Получает все записи из таблицы модели.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Реализует пагинацию для результатов запроса,
     * включая подсчет связанных записей через колбэк.
     *
     * @param int $perPage
     * @param int $currentPage
     * @return PaginationInterface
     */
    public function paginate(int $perPage = 1, int $currentPage = 1): PaginationInterface;

    /**
     * Добавляет условия для WHERE запроса.
     *
     * @param string $column
     * @param $operator
     * @param $value
     * @return $this
     */
    public function where(string $column, $operator = null, $value = null): static;

    /**
     *  Устанавливает поля для выборки.
     *
     * @param array|string $fields
     * @return static
     */
    public function select(array|string $fields): static;

    /**
     * Создать новую запись в таблице с данными $data.
     *
     * @param array $data Ассоциативный массив данных для создания записи.
     * @return mixed Созданная запись.
     */
    public function create(array $data);

    /**
     * Обновить запись с идентификатором $id данными $data.
     *
     * @param mixed $id Идентификатор обновляемой записи.
     * @param array $data Ассоциативный массив данных для обновления записи.
     * @return mixed Обновленная запись.
     */
    public function update(mixed $id, array $data): mixed;

    /**
     * Удалить запись по идентификатору.
     *
     * @param mixed $id Идентификатор удаляемой записи.
     * @return bool Возвращает true, если удаление прошло успешно.
     */
    public function delete($id): bool;

    /**
     * Возвращает название таблицы модели.
     *
     * @return ?string Название таблицы в базе данных.
     */
    public static function getTable(): ?string;

    /**
     * Устанавливает атрибуты модели
     *
     * @param array $attributes Атрибуты для установки.
     */
    public function setAttributes(array $attributes): static;

    /**
     * Устанавливает объект соединения с базой данных.
     *
     * @param $connection
     * @return void
     */
    public function setConnection($connection): void;

    /**
     * Добавляет JOIN к запросу.
     *
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @return $this
     */
    public function join(string $table, string $first, string $operator, string $second): static;
}