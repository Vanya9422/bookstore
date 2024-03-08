<?php

namespace App\Http\Requests\admin\books;

use App\Core\Request\FormRequest;
use Respect\Validation\Validator as v;

class StoreRequest extends FormRequest {

    /**
     * Определяет правила валидации для запроса добавления книги.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'title' => v::notEmpty()->stringType()->length(1, 255),
            'author_id' => v::notEmpty()->digit(),
            'description' => v::optional(v::stringType()->length(10, 1024)),
            'published_year' => v::notEmpty()->digit()->between(1000, intval(date("Y"))),
        ];
    }
}