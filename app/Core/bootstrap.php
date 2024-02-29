<?php

// Создание контейнера зависимостей (можно использовать готовую библиотеку)
$container = new \App\Core\Container\Container();

// Регистрация компонентов в контейнере
$container->bind('router', function () {
    return new \App\Core\Route\Router();
});

// Получите объект Router из контейнера
try {
    $router = $container->get('router');
} catch (Exception $e) {
    throw $e;
}

// Подключите файл с маршрутами
require __DIR__ . '/../../routes/web.php';

// Возвращаем контейнер для использования в других местах
return $container;