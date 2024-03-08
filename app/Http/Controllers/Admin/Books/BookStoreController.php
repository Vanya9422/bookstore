<?php

namespace App\Http\Controllers\Admin\Books;

use App\Http\Controllers\BaseController;
use App\Http\Requests\admin\books\StoreRequest;
use App\Repository\Client\AuthorRepositoryInterface;
use App\Repository\Client\BookRepositoryInterface;

class BookStoreController extends BaseController {

    /**
     * @throws \Exception
     */
    public function create(AuthorRepositoryInterface $authorRepository): void {
        $this->view('admin/books/create', [
            'authors' => $authorRepository->all(),
            'activePage' => 'create_books'
        ]);
    }

    /**
     * Показывает страницу входа.
     * @throws \Exception
     */
    public function store(
        StoreRequest $request,
        BookRepositoryInterface $bookRepository
    ): void {
        $book = $bookRepository->create($request->validated());

        session()->set('success', "Книга $book->title успешно добавлен.");

        $this->redirect('/admin/books');
    }
}