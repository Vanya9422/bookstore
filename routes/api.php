<?php

use App\Core\Route\Router;
use App\Http\Controllers\Api\Books\{
    DeleteBookController,
    ListBooksController,
    ShowBookController,
    UpdateBookController
};

const VERSION = 'v1';

Router::group([
    'prefix' => 'api/' . VERSION,
    'middleware' => \App\Middleware\ApiMiddleware::class
], function () {

    Router::group(['prefix' => 'books'], function () {

        Router::get('/', ListBooksController::class);

        Router::group(['prefix' => '{id}'], function () {
            Router::get('/', ShowBookController::class);
            Router::put('/', UpdateBookController::class);
            Router::delete('/', DeleteBookController::class);
        });
    });
});