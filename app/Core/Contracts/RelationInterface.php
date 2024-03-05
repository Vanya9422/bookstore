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
}