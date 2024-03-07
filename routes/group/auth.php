<?php

use App\Core\Route\Router;

Router::group([
    'prefix' => 'auth',
//    'middleware' => [GuestMiddleware::class]
], function () {
    Router::get('login', '\App\Http\Controllers\Auth\LoginController@showLogin');
    Router::post('login', '\App\Http\Controllers\Auth\LoginController@login');
});