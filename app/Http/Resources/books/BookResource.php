<?php

namespace App\Http\Resources\books;

use App\Core\Resources\JsonResource;

class BookResource extends JsonResource
{
    /**
     * @throws \Exception
     */
    public function toArray(): array {

        if ($this->resource['created_at'] === $this->resource['updated_at']) {
            $updated =  'Не Обновлено';
        } else {
            $updated = timeElapsedString($this->resource['updated_at']);
        }

        return [
            'id' => $this->resource['id'],
            'title' => $this->resource['title'],
            'description' => $this->resource['description'],
            'author_name' => $this->resource['name'],
            'publishedYear' => $this->resource['published_year'] ?? 'Unknown Year',
            'created_at' => $this->resource['created_at'],
            'updated_at' => $updated
        ];
    }
}