<?php

namespace App\Http\Controllers\Admin\Authors;

use App\Http\Controllers\BaseController;
use App\Http\Requests\admin\authors\StoreRequest;
use App\Repository\Client\AuthorRepositoryInterface;

class AuthorStoreController extends BaseController {

    /**
     * @throws \Exception
     */
    public function create(): void {
        $this->view('admin/authors/create', ['activePage' => 'create_authors']);
    }

    /**
     * Показывает страницу входа.
     * @throws \Exception
     */
    public function store(
        StoreRequest $request,
        AuthorRepositoryInterface $authorRepository
    ): void {
        $author = $authorRepository->create(
            $request->validated()
        );

        session()->set('success', "Автор {$author->name} успешно добавлен.");

        $this->redirect('/admin/authors');
    }
}