<?php

namespace App\Http\Controllers\Api\Books;

use App\Repository\Client\BookRepositoryInterface;

class DeleteBookController
{
    /**
     * Удаляет книгу по ID. Если книга не найден,
     * перенаправляет обратно с сообщением об ошибке.
     * @param BookRepositoryInterface $bookRepository
     * @param int $id ID автора для удаления.
     * @return mixed
     */
    public function __invoke(
        BookRepositoryInterface $bookRepository,
        int $id
    ): mixed {
        $book = $bookRepository->find($id);

        if (!$book) jsone()->response(['error' => 'Книга не найден.'], 404);

        $book->delete($id);

        return jsone()->response([], 204);
    }
}