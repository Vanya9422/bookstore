<?php

use App\Core\Route\Router;

Router::group([
    'prefix' => 'admin/authors',
    'middleware' => [
        \App\Middleware\AuthMiddleware::class,
        \App\Middleware\IsAdminMiddleware::class
    ]
], function () {
    Router::get('/', \App\Http\Controllers\Admin\Authors\AuthorListController::class);
    Router::get('create', '\App\Http\Controllers\Admin\Authors\AuthorStoreController@create');
    Router::post('store', '\App\Http\Controllers\Admin\Authors\AuthorStoreController@store');

    Router::group(['prefix' => '{id}'], function () {
        Router::get('edit', '\App\Http\Controllers\Admin\Authors\AuthorUpdateController@edit');
        Router::put('update', '\App\Http\Controllers\Admin\Authors\AuthorUpdateController@update');
        Router::delete('delete', \App\Http\Controllers\Admin\Authors\AuthorDeleteController::class);
    });
});