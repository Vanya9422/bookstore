<?php

namespace App\Http\Controllers\Api\Books;

use App\Http\Requests\api\BookListRequest;
use App\Http\Resources\books\BookResource;
use App\Repository\Client\BookRepositoryInterface;

class ListBooksController
{
    public function __invoke(
        BookListRequest $request,
        BookRepositoryInterface $bookRepository
    ) {
        $currentPage = $request->get('page', 1);

        $perPage = 20;

        $books = $bookRepository->bookPaginate($perPage, $currentPage);

//        $data = BookResource::make($books[0]);
        $data = BookResource::collection($books);

        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die;

        $books = $bookRepository->bookPaginate($perPage, $currentPage);

        print_r();

        return response()->json($books);
    }
}