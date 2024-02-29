<?php

use App\Core\Route\Router;

Router::get('/', \App\Controllers\HomeController::class)->middleware(\App\Middleware\AuthMiddleware::class);

//Router::prefix('tasks')->group(function () {
//    Router::get('/', \App\Controllers\HomeController::class);
//    // Добавьте столько маршрутов, сколько нужно
//})->middleware(\App\Middleware\AuthMiddleware::class);

