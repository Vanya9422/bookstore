<?php

namespace App\Http\Requests\admin\authors;

use App\Core\Request\FormRequest;
use Respect\Validation\Validator as v;

class StoreRequest extends FormRequest {

    /**
     * Определяет правила валидации для запроса добовлении автора.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'name' => v::notEmpty()->stringType()->length(1,100),
        ];
    }
}