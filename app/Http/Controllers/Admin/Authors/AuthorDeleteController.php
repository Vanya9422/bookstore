<?php

namespace App\Http\Controllers\Admin\Authors;

use App\Http\Controllers\BaseController;
use App\Repository\Client\AuthorRepositoryInterface;
class AuthorDeleteController extends BaseController {

    /**
     * Удаляет автора по ID. Если автор не найден,
     * перенаправляет обратно с сообщением об ошибке.
     * @param AuthorRepositoryInterface $authorRepository
     * @param int $id ID автора для удаления.
     * @throws \Exception
     */
    public function __invoke(
        AuthorRepositoryInterface $authorRepository,
        int $id
    ): void {
        $author = $authorRepository->find($id);

        if (!$author) back('Автор не найден.');

        $author->delete($id);

        back('Автор успешно удален.','success');
    }
}