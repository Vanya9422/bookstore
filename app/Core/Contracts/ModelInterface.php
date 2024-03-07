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
     * Создать новую запись в таблице с данными $data.
     *
     * @param array $data Ассоциативный массив данных для создания записи.
     * @return mixed Созданная запись.
     */
    public function create(array $data);

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
     * Обновить запись с идентификатором $id данными $data.
     *
     * @param mixed $id Идентификатор обновляемой записи.
     * @param array $data Ассоциативный массив данных для обновления записи.
     * @return mixed Обновленная запись.
     */
    public function update($id, array $data);

    /**
     * Удалить запись по идентификатору.
     *
     * @param mixed $id Идентификатор удаляемой записи.
     * @return bool Возвращает true, если удаление прошло успешно.
     */
    public function delete($id): bool;

    /**
     * Реализация пагинации для модели.
     *
     * @param int $perPage Количество элементов на страницу.
     * @param int $currentPage Текущая страница.
     * @return array Возвращает массив с результатами пагинации.
     */
    public function paginate(int $perPage = 1, int $currentPage = 1): array;

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
}