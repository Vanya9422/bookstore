<?php

namespace App\Core\Session;

use App\Core\Contracts\SessionManagerInterface;
use App\Models\Role;
use App\Models\User;

class SessionManager implements SessionManagerInterface {
    public function __construct()
    {
        $this->initializeSession();
    }

    public function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function delete($key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Явно сохраняет данные сессии и закрывает сессию.
     */
    public function close(): void {
        session_write_close();
    }

    /**
     * Возвращает все данные из сессии.
     *
     * @return array
     */
    public function all(): array {
        return $_SESSION;
    }

    /**
     * Очищает все данные сессии.
     */
    public function clear(): void {
        session_unset();
    }

    /**
     * Инициализирует сессию с предварительной настройкой параметров.
     */
    protected function initializeSession(): void {
        if (!$this->isSessionStarted()) {
            $this->setSessionSettings();
            session_start();
        }
    }

    public function setAuthUser(object $user): void {
        $this->set('auth_user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'created_at' => $user->created_at,
            'role' => [
                'id' => $user->role->id,
                'name' => $user->role->name,
                'created_at' => $user->role->created_at,
            ],
        ]);
    }

    public function authUser(): ?User {
        if (!$this->authCheck()) return null;

        $user = $this->get('auth_user');

        $role = (new Role)->setAttributes($user['role']);

        unset($user['role']);

        $user = (new User)->setAttributes($user);

        $user->role = $role;

        return $user;
    }

    public function authCheck(): bool
    {
        return (bool)$this->get('auth_user');
    }

    public function deleteUser(): void {
        $this->delete('auth_user');
    }

    /**
     * Проверяет, была ли сессия уже стартована.
     *
     * @return bool
     */
    protected function isSessionStarted(): bool {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * Устанавливает настройки сессии.
     */
    protected function setSessionSettings(): void {
        session_name("BOOKSTORE_APP_SESSION");

        // можно исползовать настройки из енв )
        // Более безопасные настройки куки сессии
        session_set_cookie_params([
            'lifetime' => 86400, // 1 день
            'path' => '/',
            'domain' => '', // наш домен оставим пустым, если не требуется
//            'secure' => true, // Рекомендуется для сайтов, работающих через HTTPS
//            'httponly' => true, // Доступ к куки только через HTTP(S), без JavaScript
            'samesite' => 'Lax' // Ограничивает отправку куки с cross-site запросами
        ]);
    }
}