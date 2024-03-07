<?php

namespace App\Middleware;

use App\Core\Contracts\MiddlewareInterface;
use App\Core\Session\SessionManager;

class GuardMiddleware implements MiddlewareInterface {
    public function handle() {
        $sessionManager = new SessionManager();

        if ($sessionManager->authCheck()) {
            header('Location: /');
            exit;
        }
    }
}