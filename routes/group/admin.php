<?php

use App\Core\Route\Router;
use App\Http\Controllers\Admin\{Books\ListBookController, DashboardViewController};
use App\Middleware\{AuthMiddleware, IsAdminMiddleware};

Router::group([
    'prefix' => 'admin',
    'middleware' => [AuthMiddleware::class, IsAdminMiddleware::class]
], function () {

    Router::get('dashboard', DashboardViewController::class);
    Router::get('books', ListBookController::class);

    Router::group(['prefix' => 'books'], function () {
        Router::get('/', ListBookController::class);
    });

    Router::get('authors', \App\Http\Controllers\Admin\AuthorListController::class);
});