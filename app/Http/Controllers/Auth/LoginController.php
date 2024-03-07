<?php

namespace App\Http\Controllers\Auth;

use App\Core\Contracts\SessionManagerInterface;
use App\Http\Controllers\BaseController;
use App\Http\Requests\LoginRequest;
use App\Repository\Client\UserRepository;

class LoginController extends BaseController {

    /**
     * Показывает страницу входа.
     * @throws \Exception
     */
    public function showLogin(): void {
        $this->view('auth/login', ['activePage' => 'login']);
    }

    /**
     * Обрабатывает попытку входа в систему.
     * @throws \Exception
     */
    public function login(
        LoginRequest $request,
        UserRepository $repository,
        SessionManagerInterface $sessionManager
    ): void {
        $email = $request->get('email');
        $password = $request->get('password');

        try {
            $user = $repository->findByEmail($email, ['role']);

            if (!$user) {
                $sessionManager->set('validation_errors', ['email' => ["Пользователь с почтой $email не найден."]]);
                $sessionManager->set('old', ['email' => $email]);
                back();
            }

            if (!password_verify($password, $user->getPassword())) {
                $sessionManager->set('validation_errors', ['email' => ["Почта или Пароль не правильно"]]);
                $sessionManager->set('old', ['email' => $email]);
                back();
            }

            $sessionManager->setAuthUser($user);

            $this->redirect('/admin/dashboard');
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }
}