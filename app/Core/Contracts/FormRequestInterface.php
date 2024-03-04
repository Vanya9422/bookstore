<?php

namespace App\Core\Contracts;

/**
 * Интерфейс для форм запроса с валидацией.
 */
interface FormRequestInterface extends RequestInterface
{
    /**
     * Валидирует данные запроса с использованием определенных правил.
     *
     * @return bool Возвращает true, если данные прошли валидацию, иначе false.
     */
    public function validate(): bool;

    /**
     * Возвращает массив правил валидации.
     *
     * @return array Массив правил.
     */
    public function rules(): array;

    /**
     * Проверяет, есть ли ошибки валидации.
     *
     * @return bool Возвращает true, если есть ошибки валидации, иначе false.
     */
    public function fails(): bool;

    /**
     * Возвращает массив ошибок валидации.
     *
     * @return array Массив ошибок.
     */
    public function errors(): array;

    /**
     * Возвращает только проверенные и прошедшие валидацию данные.
     *
     * Этот метод должен быть вызван после успешной валидации. Он возвращает ассоциативный массив данных,
     * которые были проверены и соответствуют заданным правилам валидации.
     *
     * @return array Ассоциативный массив проверенных данных.
     */
    public function validated(): array;

    /**
     * Возвращает экземпляр валидатора для проверки данных запроса.
     */
    public function getValidator();
}