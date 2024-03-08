<?php

use App\Core\Route\Router;

Router::group(['prefix' => 'auth'], function () {

    Router::group([
        'middleware' => \App\Middleware\GuardMiddleware::class
    ], function () {
        Router::get('login', '\App\Http\Controllers\Auth\LoginController@showLogin');
        Router::post('login', '\App\Http\Controllers\Auth\LoginController@login');
    });

    Router::post('logout', function () {
        session()->deleteUser();
        header('Location: /auth/login');
        exit;
    }, \App\Middleware\AuthMiddleware::class);
});