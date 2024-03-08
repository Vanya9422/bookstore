<?php

use App\Core\Route\Router;
use App\Http\Controllers\Admin\DashboardViewController;
use App\Middleware\{AuthMiddleware, IsAdminMiddleware};

Router::group([
    'prefix' => 'admin',
    'middleware' => [AuthMiddleware::class, IsAdminMiddleware::class]
], function () {
    Router::get('dashboard', DashboardViewController::class);
});