<?php

namespace App\Http\Controllers;

class BaseController
{
    /**
     * @throws \Exception
     */
    protected function view(string $viewPath, array $data = []): void
    {
        // Путь к файлам представлений может быть задан глобально или передан напрямую
        $viewFilePath = __DIR__ . '/../../../views/' . $viewPath . '.php';

        if (!file_exists($viewFilePath)) {
            throw new \Exception("View file does not exist: {$viewPath}");
        }

        // Экстрактим данные для использования в виде переменных в представлении
        extract($data);

        require $viewFilePath;
    }

    /**
     * @param string $route
     * @return void
     */
    protected function redirect(string $route): void
    {
        header("Location: $route");

        exit;
    }
}