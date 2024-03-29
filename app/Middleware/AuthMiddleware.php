<?php

namespace App\Middleware;

use App\Core\Contracts\MiddlewareInterface;
use App\Core\Session\SessionManager;

class AuthMiddleware implements MiddlewareInterface {
    public function handle() {
        $sessionManager = new SessionManager();

        if (!$sessionManager->get('auth_user')) {
            header('Location: /auth/login');
            exit;
        }
    }
}