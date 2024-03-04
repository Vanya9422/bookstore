<?php

namespace App\Http\Controllers\Client;

use App\Core\Request\Request;
use App\Http\Controllers\BaseController;
use App\Repository\Tasks\AuthorRepositoryInterface;

class HomeController extends BaseController {

    /**
     * @throws \Exception
     */
    public function __invoke(
        Request $request,
        AuthorRepositoryInterface $authorRepository
    ): void {
        $currentPage = $request->get('page', 1);

        // Количество записей на странице
        $perPage = 3;

        // Получение авторов с их книгами с пагинацией
        $authors = $authorRepository->paginate($perPage, $currentPage, ['books']);

        $this->view('client/home', ['authors' => $authors, 'activePage' => 'home']);
    }
}