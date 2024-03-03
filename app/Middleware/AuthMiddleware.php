<?php

namespace App\Middleware;

use App\Core\Contracts\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface {
    public function handle() {
        if (!$this->isAuthenticated()) {
//            header('Location: /login');

            $dbHost = env('DB_DATABASE');

            print_r($dbHost);

            exit('Ashxatav Brats');
        }
    }

    private function isAuthenticated(): bool
    {
        return false;
//        return isset($_SESSION['user_id']);
    }
}