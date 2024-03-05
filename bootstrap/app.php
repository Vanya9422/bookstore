<?php

use App\Core\Application;
use App\Core\Bootstrappers\{
    RouteBootstrapper,
    ServicesBootstrapper
};

Dotenv\Dotenv::createImmutable(__DIR__ . '/../')->load();

$app = new Application();

$app->addBootstrapper(new ServicesBootstrapper()); // Добавление загрузчика сервисов

// Добавление загрузчиков
$app->addBootstrapper(new RouteBootstrapper());

$app->run(); // Запуск приложения