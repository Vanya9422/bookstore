<?php

namespace App\Models;

use App\Core\Database\Model;

class Book extends Model {

    public function author(): object {
        return $this->belongsTo(Author::class, 'author_id');
    }
}