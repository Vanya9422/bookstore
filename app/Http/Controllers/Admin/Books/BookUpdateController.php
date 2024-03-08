<?php

namespace App\Http\Controllers\Admin\Books;

use App\Http\Controllers\BaseController;
use App\Http\Requests\admin\books\StoreRequest;
use App\Repository\Client\AuthorRepositoryInterface;
use App\Repository\Client\BookRepositoryInterface;

class BookUpdateController extends BaseController {

    /**
     * @param BookRepositoryInterface $repository
     */
    public function __construct(private readonly BookRepositoryInterface $repository) {}

    /**
     * @throws \Exception
     */
    public function edit(AuthorRepositoryInterface $authorRepository, $id): void {
        $book = $this->repository->find($id);
        $authors = $authorRepository->all();

        $this->view('admin/books/edit', compact(['book', 'authors']));
    }

    /**
     * Показывает страницу входа.
     * @throws \Exception
     */
    public function update(StoreRequest $request, $id): void {
        $author = $this->repository->update($id, $request->validated());

        back("Книга $author->title успешно обновлен.");
    }
}