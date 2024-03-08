<?php

namespace App\Http\Controllers\Admin\Authors;

use App\Core\Request\Request;
use App\Http\Controllers\BaseController;
use App\Repository\Client\AuthorRepositoryInterface;

class AuthorListController extends BaseController
{
    /**
     * Показывает страницу входа.
     * @throws \Exception
     */
    public function __invoke(
        Request $request,
        AuthorRepositoryInterface $authorRepository
    ): void {
        $currentPage = $request->get('page', 1);

        $perPage = 20;

        $authors = $authorRepository->authorPaginate($perPage, $currentPage);

        $this->view('admin/authors/list', [
            'list' => $authors,
            'activePage' => 'authors'
        ]);
    }
}