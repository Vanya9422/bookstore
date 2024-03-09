<?php

use App\Core\Route\Router;

const VERSION = 'v1';

Router::group([
    'prefix' => 'api/' . VERSION,
    /*'middleware' => []*/
], function () {

    Router::group(['prefix' => 'books'], function () {
        Router::get('/', \App\Http\Controllers\Api\Books\ListBooksController::class);

//        Router::group(['prefix' => '{id}'], function () {
//            Router::get('/', '\App\Http\Controllers\Api\Books\ShowBookController@update');
//            Router::put('/', '\App\Http\Controllers\Api\Books\UpdateBookController@update');
//            Router::delete('/', \App\Http\Controllers\Api\Books\DeleteBookController::class);
//        });
    });
});