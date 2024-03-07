<?php

namespace App\Http\Controllers\Admin\Books;

use App\Core\Request\Request;
use App\Http\Controllers\BaseController;
use App\Repository\Client\BookRepositoryInterface;

class ListBookController extends BaseController
{
    /**
     * Показывает страницу входа.
     * @throws \Exception
     */
    public function __invoke(
        Request $request,
        BookRepositoryInterface $bookRepository
    ): void {
        $currentPage = $request->get('page', 1);

        // Количество записей на странице
        $perPage = 20;

        // Получение авторов с их книгами с пагинацией
        $books = $bookRepository->bookPaginate($perPage, $currentPage);

        $this->view('admin/books/list', ['list' => $books,'activePage' => 'books']);
    }
}