<?php

use App\Core\Route\Router;

Router::prefix('auth')->group(function () {
    Router::get('login', '\App\Http\Controllers\Auth\LoginController@showLogin');
    Router::post('login', '\App\Http\Controllers\Auth\LoginController@login');
});