<?php

namespace App\Core\Contracts;

/**
 * Интерфейс RequestInterface определяет основные методы для работы с запросами.
 * Это позволяет абстрагироваться от конкретной реализации класса запроса и упростить тестирование.
 */
interface RequestInterface {

    /**
     * Получает значение указанного параметра запроса.
     *
     * @param string $key Ключ параметра запроса.
     * @param mixed $default Значение по умолчанию, возвращаемое, если параметр не найден.
     * @return mixed Значение параметра запроса или значение по умолчанию.
     */
    public function get($key, $default = null);

    /**
     * Проверяет наличие указанного параметра в запросе.
     *
     * @param string $key Ключ параметра запроса.
     * @return bool Возвращает true, если параметр присутствует, иначе false.
     */
    public function has($key): bool;

    /**
     * Возвращает все параметры запроса.
     *
     * @return array Массив всех параметров запроса.
     */
    public function all(): array;
}