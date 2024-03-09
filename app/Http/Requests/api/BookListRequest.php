<?php

namespace App\Http\Requests\api;

use App\Core\Request\FormRequest;

class BookListRequest extends FormRequest {

    /**
     * Определяет правила валидации для запроса логина.
     *
     * @return array
     */
    public function rules(): array {
        return [];
    }
}