<?php

use App\Core\Route\Router;

Router::group([
    'prefix' => 'admin/books',
    'middleware' => [
        \App\Middleware\AuthMiddleware::class,
        \App\Middleware\IsAdminMiddleware::class
    ]
], function () {

    Router::get('/', \App\Http\Controllers\Admin\Books\BookListController::class);
    Router::get('create', '\App\Http\Controllers\Admin\Books\BookStoreController@create');
    Router::post('store', '\App\Http\Controllers\Admin\Books\BookStoreController@store');

    Router::group(['prefix' => '{id}'], function () {
        Router::get('edit', '\App\Http\Controllers\Admin\Books\BookUpdateController@edit');
        Router::put('update', '\App\Http\Controllers\Admin\Books\BookUpdateController@update');
        Router::delete('delete', \App\Http\Controllers\Admin\Books\BookDeleteController::class);
    });
});