<?php

namespace App\Core\Route;

use App\Core\Contracts\RouterInterface;

class Router implements RouterInterface {

    protected static $routes = [
        'GET' => [],
        'POST' => [],
        'DELETE' => [],
        'PUT' => [],
        'PATCH' => [],
        'OPTIONS' => [],
    ];

    protected static $prefix = '';

    // Добавляем стек для хранения префиксов
    protected static $prefixStack = [];

    public static function get($uri, $controller) {
        static::addRoute('GET', $uri, $controller);
    }

    public static function post($uri, $controller) {
        static::addRoute('POST', $uri, $controller);
    }

    /**
     * @throws \Exception
     */
    public function dispatch() {
        $uri = $this->getUri();
        $method = $this->getMethod();

        if (isset(static::$routes[$method][$uri])) {
            // Получаем маршрут
            $route = static::$routes[$method][$uri];

            // Если маршрут строка, разделяем контроллер и метод
            if (is_string($route)) {
                return $this->callAction(...explode('@', $route));
            }

            // Если маршрут массив, передаём его напрямую в callAction (контроллер и метод)
            if (is_array($route)) {
                return $this->callAction($route[0], $route[1]);
            }
        }

        header("HTTP/2 404 Not Found");

        exit;
    }

    public static function prefix($prefix) {
        self::$prefixStack[] = self::$prefix; // Сохраняем текущий префикс в стек

        self::$prefix .= '/' . trim($prefix, '/');

        return new static; // Возвращаем экземпляр для цепочного вызова
    }

    public static function group(callable $callback) {
        call_user_func($callback);

        // Восстанавливаем предыдущий префикс из стека
        self::$prefix = array_pop(self::$prefixStack);
    }

    protected static function addRoute($method, $uri, $controller) {
        $uri = static::$prefix . '/' . trim($uri, '/');
        $uri = $uri !== '/' ? trim($uri, '/') : $uri; // Удаляем начальный слеш, если нет префикса

        if (is_string($controller) && strpos($controller, '@') !== false) {
            static::$routes[$method][$uri] = $controller;
        } else {
            static::$routes[$method][$uri] = [$controller, null];
        }
    }

    public function getUri() {
        // Получаем URI и удаляем пробельные символы с обоих концов строки
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        // Если URI пуст, возвращаем '/'
        return $uri === '' ? '/' : $uri;
    }

    /**
     * @throws \Exception
     */
    protected function callAction($controller, $action) {
        if (!class_exists($controller)) {
            throw new \Exception("Controller {$controller} not found.");
        }

        $controllerInstance = new $controller();

        // Если $action не указан, пытаемся вызвать __invoke
        if ($action === null) {
            if (!method_exists($controllerInstance, '__invoke')) {
                throw new \Exception("Controller {$controller} is not invokable.");
            }

            return $controllerInstance();
        }

        // Если $action указан, вызываем указанный метод
        if (!method_exists($controllerInstance, $action)) {
            throw new \Exception("{$controller} does not respond to the {$action} action.");
        }

        return $controllerInstance->$action();
    }

    protected function getMethod() {
        return $_SERVER['REQUEST_METHOD']; // Получаем HTTP-метод
    }
}