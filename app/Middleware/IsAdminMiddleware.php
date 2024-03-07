<?php

namespace App\Middleware;

use App\Core\Contracts\MiddlewareInterface;
use App\Core\Session\SessionManager;

class IsAdminMiddleware implements MiddlewareInterface {
    public function handle() {
        $sessionManager = new SessionManager();

        $user = $sessionManager->authUser();

        if (!$user->isAdmin()) {
            $sessionManager->set('errors', ['Доступ запрещен. Требуются права администратора.']);
            header('Location: /');
            exit;
        }
    }
}