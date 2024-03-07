<?php

namespace App\Traits;

trait EnumTrait
{
    /**
     * Метод для получения всех значений перечисления.
     *
     * @return array Массив со всеми значениями перечисления.
     */
    public static function getValues(): array
    {
        return array_map(fn($case) => $case->value, static::cases());
    }

    /**
     * Метод для получения всех ключей перечисления.
     *
     * @return array Массив со всеми ключами перечисления.
     */
    public static function getKeys(): array
    {
        return array_map(fn($case) => $case->name, static::cases());
    }

    /**
     * Метод для проверки, существует ли заданное значение в перечислении.
     *
     * @param mixed $value Значение, которое необходимо проверить.
     * @return bool Возвращает true, если значение существует в перечислении, иначе false.
     */
    public static function isValidValue($value): bool
    {
        return in_array($value, static::getValues(), true);
    }

    /**
     * Метод для проверки, существует ли заданный ключ в перечислении.
     *
     * @param string $key Ключ, который необходимо проверить.
     * @return bool Возвращает true, если ключ существует в перечислении, иначе false.
     */
    public static function isValidKey(string $key): bool
    {
        return in_array($key, static::getKeys(), true);
    }
}