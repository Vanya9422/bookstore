<?php

namespace App\Core\Route;

use App\Core\Contracts\RouterInterface;

class Router implements RouterInterface {
    protected array $routes = [
        'GET' => [],
        'POST' => [],
        'DELETE' => [],
        'PUT' => [],
        'PATCH' => [],
        'OPTIONS' => [],
    ];

    protected static ?Router $instance = null;
    protected string $prefix = '';
    protected array $middlewareStack = [];
    protected array $prefixStack = [];
    protected array $currentGroupMiddleware = [];

    /**
     * Синглтон паттерн: метод для получения экземпляра
     *
     * @return Router|null
     */
    public static function getInstance(): ?Router {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Регистрируем маршрут
     *
     * @param string $method
     * @param string $uri
     * @param $controller
     * @return void
     */
    public function addRoute(string $method, string $uri, $controller): void {
        $uri = $this->prefix . '/' . trim($uri, '/');
        $uri = $uri !== '/' ? trim($uri, '/') : $uri;

        $this->routes[strtoupper($method)][$uri] = [
            'controller' => $controller,
            'middleware' => array_merge($this->currentGroupMiddleware, $this->middlewareStack),
        ];

        $this->middlewareStack = [];
    }

    /**
     * Обрабатываем запрос
     *
     * @return void
     * @throws \Exception
     */
    public function dispatch() {
        $uri = $this->getUri();
        $method = $this->getMethod();

        foreach (static::getInstance()->routes[$method] as $routeUri => $routeInfo) {
            if (preg_match('#^' . $routeUri . '$#', $uri, $matches)) {
                // Middleware
                foreach ($routeInfo['middleware'] as $middleware) {
                    $middlewareInstance = new $middleware();
                    $middlewareInstance->handle();
                }

                // Controller
                return $this->callAction(
                    ...explode('@', $routeInfo['controller'])
                );
            }
        }

        // Если маршрут не найден
        $this->sendNotFound();
    }

    /**
     * Получаем URI
     *
     * @return string
     */
    public function getUri(): string
    {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        return $uri === '' ? '/' : $uri;
    }

    /**
     * Универсальный вызов для регистрации маршрутов
     *
     * @param $method
     * @param $args
     * @return Router
     */
    public static function __callStatic($method, $args) {
        call_user_func_array([static::getInstance(), 'addRoute'], [$method, ...$args]);

        return static::getInstance();
    }

    /**
     * Устанавливаем префикс для группы маршрутов
     * @param $prefix
     * @return void
     */
    public static function prefix($prefix): Router {
        $instance = static::getInstance();
        $instance->prefixStack[] = [$instance->prefix, $instance->currentGroupMiddleware];
        $instance->prefix .= '/' . trim($prefix, '/');
        return $instance;
    }

    /**
     * @param callable $callback
     * @return Router
     */
    public static function group(callable $callback): Router {
        $instance = static::getInstance();
        call_user_func($callback);

        [$instance->prefix, $instance->currentGroupMiddleware] = array_pop($instance->prefixStack) ?: ['', []];
        return $instance;
    }

    /**
     * @param $middleware
     * @return $this
     */
    public function middleware($middleware): Router {
        if (empty($this->prefixStack)) {
            $this->middlewareStack[] = $middleware;
        } else {
            $this->currentGroupMiddleware[] = $middleware;
        }
        return $this;
    }

    /**
     * @throws \Exception
     */
    protected function callAction($controller, $action = null) {
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
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return void
     */
    protected function sendNotFound() {
        header("HTTP/1.0 404 Not Found");
        exit('404 Not Found');
    }
}