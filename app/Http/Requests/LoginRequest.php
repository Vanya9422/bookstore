<?php

namespace App\Http\Requests;

use App\Core\Request\FormRequest;
use Respect\Validation\Validator as v;

class LoginRequest extends FormRequest {

    /**
     * Определяет правила валидации для запроса логина.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'email' => v::notEmpty()->email()->setName('Почта пользователя'),
            'password' => v::notEmpty()->length(6)->setName('Пароль'),
        ];
    }
}