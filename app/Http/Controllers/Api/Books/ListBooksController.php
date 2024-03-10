<?php

namespace App\Http\Controllers\Api\Books;

use App\Http\Requests\api\BookListRequest;
use App\Http\Resources\BookResource;
use App\Repository\Client\BookRepositoryInterface;

class ListBooksController
{
    public function __invoke(
        BookListRequest $request,
        BookRepositoryInterface $bookRepository
    ) {
        $currentPage = $request->get('page', 1);
        $limit = $request->get('limit', 20);

        $books = $bookRepository->bookPaginate($limit, $currentPage);

        return jsone()->response(BookResource::collection($books));
    }
}