<?php

namespace App\Http\Controllers\Auth;

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
    public function login(LoginRequest $request): void {
        try {
            $request->validate();
//            $username = $request->get('email');
//            $password = $request->get('password');
            print_r('awdawd');
            print_r($request->all());
            die;

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

            $this->redirect('auth/login');
        }
    }
}