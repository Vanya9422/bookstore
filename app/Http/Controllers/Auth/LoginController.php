<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\LoginRequest;

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
    public function login(LoginRequest $request): void {
        try {


        } catch (\App\Exceptions\Validation\ValidationException $e) {
            $this->view('auth/login', [
                'errors' => $request->errors(),
                'activePage' => 'login'
            ]);
        }
    }
}