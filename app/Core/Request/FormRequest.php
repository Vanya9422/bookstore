<?php

namespace App\Core\Request;

use App\Core\Application;
use App\Core\Contracts\FormRequestInterface;
use App\Core\Contracts\RequestInterface;
use App\Core\Contracts\SessionManagerInterface;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Respect\Validation\Validator;

abstract class FormRequest extends Request implements RequestInterface, FormRequestInterface {
    protected array $errors = [];

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function __construct() {
        parent::__construct();
        $this->validate();
    }

    /**
     * Валидирует входные данные запроса с использованием правил, определенных в классе наследнике.
     *
     * @return bool Возвращает true, если данные прошли валидацию успешно.
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function validate(): bool {
        $rules = $this->rules();

        foreach ($rules as $field => $rule) {
            try {
                // Проверяем каждое поле с его правилом
                $rule->assert($this->get($field));
            } catch (Exception $exception) {
                // Собираем ошибки для каждого поля
                $this->errors[$field] = $exception->getMessages();
            }
        }

        if ($this->fails()) {
            $session = Application::getContainer()->get(SessionManagerInterface::class);
            $session->set('validation_errors', $this->errors);
            $session->set('old', $this->all());
            back();
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
     * @return array
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