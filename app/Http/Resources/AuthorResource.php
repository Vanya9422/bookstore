<?php

namespace App\Http\Resources;

use App\Core\Resources\JsonResource;

class AuthorResource extends JsonResource
{
    /**
     * @throws \Exception
     */
    public function toArray(): array {
        return [
            'id' => $this->resource['id'],
            'name' => $this->resource['name'],
            'created_at' => $this->resource['created_at'],
        ];
    }
}