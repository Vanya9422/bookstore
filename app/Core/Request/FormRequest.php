<?php

namespace App\Core\Request;

use App\Core\Contracts\FormRequestInterface;
use App\Core\Contracts\RequestInterface;
use App\Exceptions\Validation\ValidationException;
use Exception;
use Respect\Validation\Validator;

abstract class FormRequest extends Request implements RequestInterface, FormRequestInterface {
    protected array $errors = [];

    /**
     * Валидирует входные данные запроса с использованием правил, определенных в классе наследнике.
     *
     * @throws ValidationException Если данные не прошли валидацию.
     * @return bool Возвращает true, если данные прошли валидацию успешно.
     */
    public function validate(): bool {
        $rules = $this->rules();
        $validator = $this->getValidator();

        foreach ($rules as $field => $rule) {
            try {
                // Проверяем каждое поле с его правилом
                $validator::attribute($field, $rule)->assert($this->all());
            } catch (Exception $exception) {
                // Собираем ошибки для каждого поля
                $this->errors[$field] = $exception->getMessages();
            }
        }

        if ($this->fails()) {
            // Если есть ошибки, бросаем исключение с ними
            throw new ValidationException($this->errors, "Validation errors");
        }

        return true;
    }

    /**
     * Должен быть реализован в классе наследнике для определения правил валидации.
     *
     * @return array Массив правил валидации.
     */
    abstract public function rules(): array;

    /**
     * Проверяет, были ли ошибки валидации.
     *
     * @return bool Возвращает true, если были ошибки валидации.
     */
    public function fails(): bool {
        return !empty($this->errors);
    }

    /**
     * Возвращает ошибки валидации.
     *
     * @return array Массив ошибок.
     */
    public function errors(): array {
        return $this->errors;
    }

    /**
     * @throws ValidationException
     */
    public function validated(): array
    {
        $this->validate();

        $validatedData = [];
        foreach ($this->rules() as $field => $rule) {
            $validatedData[$field] = $this->get($field);
        }

        return $validatedData;
    }

    /**
     * Возвращает экземпляр валидатора для проверки данных запроса.
     *
     * @return Validator Валидатор данных запроса.
     */
    public function getValidator(): Validator {
        return new Validator();
    }
}