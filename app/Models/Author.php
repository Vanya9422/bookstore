<?php

namespace App\Models;



use App\Core\Database\Model;

class Author extends Model {
    public function books($ids): array {
        return $this->hasMany(Book::class, 'author_id', $ids);
    }
}