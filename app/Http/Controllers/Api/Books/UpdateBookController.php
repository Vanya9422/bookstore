<?php

namespace App\Http\Controllers\Api\Books;

use App\Http\Requests\admin\books\StoreRequest;
use App\Http\Resources\BookResource;
use App\Repository\Client\BookRepositoryInterface;

class UpdateBookController {

    /**
     * Показывает страницу входа.
     * @throws \Exception
     */
    public function __invoke(
        StoreRequest $request,
        BookRepositoryInterface $bookRepository,
        $id
    ): void {
        $book = $bookRepository->update($id, $request->validated());

        if (!$book) {
            jsone()->response([
                'error' => true,
                'message' => "Книга не найдена"
            ], 404);
        }

        jsone()->response([
            'success' => true,
            'message' => "Книга $book->title успешно обновлен.",
            'book' => BookResource::make($book)
        ]);
    }
}