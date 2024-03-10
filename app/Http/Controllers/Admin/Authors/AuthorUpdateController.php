<?php

namespace App\Http\Controllers\Admin\Authors;

use App\Http\Controllers\BaseController;
use App\Http\Requests\admin\authors\StoreRequest;
use App\Repository\Client\AuthorRepositoryInterface;

class AuthorUpdateController extends BaseController {

    /**
     * @param AuthorRepositoryInterface $repository
     */
    public function __construct(private readonly AuthorRepositoryInterface $repository) {}

    /**
     * @throws \Exception
     */
    public function edit($id): void {
        $author = $this->repository->find($id, ['books']);

        $this->view('admin/authors/edit', compact('author'));
    }

    /**
     * Показывает страницу входа.
     * @throws \Exception
     */
    public function update(StoreRequest $request, $id): void {
        $author = $this->repository->update($id, $request->validated());

        if (!$author) back(["Автор $author->name Не обновлен."], 'errors');

        back("Автор $author->name успешно обновлен.");
    }
}