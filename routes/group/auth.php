<?php

use App\Core\Route\Router;

Router::prefix('auth')->group(function () {
    Router::get('/', \App\Controllers\HomeController::class);
    // Добавьте столько маршрутов, сколько нужно
})/*->middleware(\App\Middleware\AuthMiddleware::class)*/;