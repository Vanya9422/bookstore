<?php

namespace App\Http\Resources;

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

        $data = [
            'id' => $this->resource['id'],
            'title' => $this->resource['title'],
            'description' => $this->resource['description'],
            'publishedYear' => $this->resource['published_year'] ?? 'Unknown Year',
            'created_at' => $this->resource['created_at'],
            'updated_at' => $updated
        ];

        if (isset($this->resource['name'])) {
            $data['author_name'] = $this->resource['name'];
        }

        if (isset($this->resource['author'])) {
            $data['author'] = AuthorResource::make($this->resource['author']);
        }

        return $data;
    }
}