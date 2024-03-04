<?php

namespace App\Core\Route;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use App\Core\Contracts\RouterInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @method static post(string $string, string|array $array)
 * @method static get(string $string, string|array $string1)
 */
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
     * @param ContainerInterface $container
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function dispatch(ContainerInterface $container): void {
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
                $this->callAction(
                    $container, ...explode('@', $routeInfo['controller'])
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
     * Вызывает указанный метод контроллера, автоматически внедряя зависимости.
     *
     * @param ContainerInterface $container Контейнер зависимостей для автоматического внедрения.
     * @param string $controller Имя класса контроллера.
     * @param string|null $action Имя метода в контроллере для вызова.
     * @return mixed Результат выполнения метода контроллера.
     * @throws ContainerExceptionInterface Если контейнер не может вернуть элемент.
     * @throws NotFoundExceptionInterface Если элемент не найден в контейнере.
     * @throws \Exception Если контроллер или метод не найдены, или параметры метода не могут быть разрешены.
     */
    protected function callAction(ContainerInterface $container, string $controller, string $action = null): mixed {
        // Проверяем существование класса контроллера
        if (!class_exists($controller)) {
            throw new \Exception("Controller {$controller} not found.");
        }

        // Получаем экземпляр контроллера из контейнера
        $controllerInstance = $container->get($controller);

        // Определяем метод для вызова
        $methodName = $action ?? '__invoke';
        if (!method_exists($controllerInstance, $methodName)) {
            throw new \Exception("{$controller} does not respond to the {$methodName} action.");
        }

        // Анализируем параметры метода с использованием рефлексии
        $method = new \ReflectionMethod($controllerInstance, $methodName);
        $parameters = $method->getParameters();

        // Подготавливаем аргументы для вызова метода
        $args = [];
        foreach ($parameters as $parameter) {
            $parameterType = $parameter->getType();
            if (!$parameterType) {
                throw new \Exception("Cannot resolve the parameter `{$parameter->getName()}` in method `{$methodName}` of controller `{$controller}`. Type hint is missing.");
            }
            $parameterTypeName = $parameterType->getName();
            if ($container->has($parameterTypeName)) {
                $args[] = $container->get($parameterTypeName);
            } else {
                throw new \Exception("The required service `{$parameterTypeName}` is not configured in the container.");
            }
        }

        // Вызываем метод контроллера с подготовленными аргументами
        return $method->invokeArgs($controllerInstance, $args);
    }

    protected function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return void
     */
    protected function sendNotFound(): void
    {
        header("HTTP/1.0 404 Not Found");
        exit('404 Not Found');
    }
}