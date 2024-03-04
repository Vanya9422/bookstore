<?php

use App\Core\Route\Router;

Router::get('/', \App\Controllers\HomeController::class);

findFiles(__DIR__ . '/../routes/group');