<?php

namespace App\Http\Controllers\Admin\Books;

use App\Http\Controllers\BaseController;
use App\Repository\Client\BookRepositoryInterface;

class BookDeleteController extends BaseController {

    /**
     * Удаляет книгу по ID. Если книга не найден,
     * перенаправляет обратно с сообщением об ошибке.
     * @param BookRepositoryInterface $bookRepository
     * @param int $id ID автора для удаления.
     */
    public function __invoke(
        BookRepositoryInterface $bookRepository,
        int $id
    ): void {
        $book = $bookRepository->find($id);

        if (!$book) back(['Книга не найден.'], 'errors');

        $book->delete($id);

        back('Книга успешно удален.');
    }
}