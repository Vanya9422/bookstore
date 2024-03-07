<?php

namespace App\Core\Contracts;

interface RelationInterface
{
    /**
     * Определяет связь "один ко многим" между моделями.
     *
     * @param string $relatedClass
     * @param string $foreignKey
     * @param array $ids
     * @return array
     */
    public function hasMany(string $relatedClass, string $foreignKey, array $ids): array;

    /**
     * Определяет обратное отношение "многие к одному" между моделями.
     *
     * @param string $relatedClass Имя класса связанной модели.
     * @param string $foreignKey Внешний ключ в текущей модели, указывающий на связанную модель.
     * @param string $ownerKey Первичный ключ в связанной модели, по умолчанию 'id'.
     * @return ?object Возвращает объект связанной модели или null, если связь не найдена.
     */
    public function belongsTo(string $relatedClass, string $foreignKey, string $ownerKey = 'id'): ?object;

    /**
     * Указывает связи, которые должны быть загружены вместе с основными результатами.
     *
     * @param array|string $relations
     * @return static
     */
    public function with(array|string $relations):  static;

    /**
     * Загружает указанные связи для результатов.
     *
     * @param array|object $results
     * @return array|object
     */
    public function loadRelations(array|object $results):  array|object;
}