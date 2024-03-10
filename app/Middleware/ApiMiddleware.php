<?php

namespace App\Middleware;

use App\Core\Contracts\MiddlewareInterface;

class ApiMiddleware implements MiddlewareInterface {
    public function handle() {
        // Проверяем, что Accept: application/json
        if (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') === false) {
            http_response_code(406); // Unauthorized
            echo json_encode(['error' => 'Invalid Accept header']);
            exit;
        }

        // можно добавить любые проверки например токен итд
    }
}