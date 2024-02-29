<?php

require __DIR__ . '/../vendor/autoload.php';

// Получаем контейнер
$container = require_once __DIR__ . '/../app/Core/bootstrap.php';

// Получаем роутер из контейнера
/** @var \App\Core\Route\Router $router */
$router = $container->get('router');

// Определяем маршруты
require __DIR__ . '/../routes/web.php';

// Запускаем маршрутизацию
try {
    $router->dispatch();
} catch (Exception $e) {
    throw $e;
}