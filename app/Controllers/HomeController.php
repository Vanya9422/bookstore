<?php

namespace App\Controllers;

use App\Core\Contracts\RequestInterface;
use App\Repository\Tasks\AuthorRepositoryInterface;

class HomeController extends BaseController {

    public function __construct(
        private AuthorRepositoryInterface$authorRepository,
        private RequestInterface $request
    ) {}

    /**
     * @throws \Exception
     */
    public function __invoke(): void {
        $currentPage = $this->request->get('page', 1);

        // Количество записей на странице
        $perPage = 10;

        // Получение авторов с их книгами с пагинацией
        $authors = $this->authorRepository->paginate($perPage, $currentPage, ['books']);

        $this->view('home', ['authors' => $authors, 'activePage' => 'home']);
    }
}