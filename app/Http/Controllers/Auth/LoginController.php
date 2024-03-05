<?php

namespace App\Http\Controllers\Auth;

use App\Core\Contracts\SessionManagerInterface;
use App\Http\Controllers\BaseController;
use App\Http\Requests\LoginRequest;

class LoginController extends BaseController {

//    protected Session $session;
//
//    public function __construct()
//    {
//        $this->session = Session::getFacadeRoot();
//    }

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
    public function login(LoginRequest $request, SessionManagerInterface $sessionManager): void {
        try {
            $request->validate();

            // Сброс предыдущих значений и ошибок валидации
            $sessionManager->delete('errors');
            $sessionManager->delete('old');

//            $username = $request->get('email');
//            $password = $request->get('password');

            // Здесь должна быть логика поиска пользователя в репозитории
            // и проверка его учетных данных
//            $user = $this->userRepository->findByEmail($username);

//            if ($user && password_verify($password, $user->getPassword())) {
//                // Успешный вход
//                // Здесь должен быть код для установки пользовательских сессий или токенов
//                header('Location: /profile');
//                exit;
//            }
            // Успешный вход
        } catch (\App\Exceptions\Validation\ValidationException $e) {
            // Сохранение ошибок валидации и введенных данных в сессию
            $sessionManager->set('errors', $e->getErrors());
            $sessionManager->set('old', ['email' => $request->get('email')]);

//            echo '<pre>';
//            print_r($sessionManager->all());
//            echo '</pre>';
//            die;

            $this->redirect('/auth/login');
        }
    }
}