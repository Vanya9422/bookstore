<?php

namespace App\Http\Controllers\Api\Books;

use App\Http\Resources\BookResource;
use App\Repository\Client\BookRepositoryInterface;

class ShowBookController {
    public function __invoke(
        BookRepositoryInterface $bookRepository, int $id
    ) {
        $book = $bookRepository->find($id, ['author']);

        if (!$book) return jsone()->response(['error' => 'Книга не найдена'], 404); // можно исползовать константи для кода

        return jsone()->response(BookResource::make($book));
    }
}