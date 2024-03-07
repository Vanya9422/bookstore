<?php

namespace App\Core\Route;

use App\Core\Contracts\MiddlewareInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use App\Core\Contracts\RouterInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @method static get(string $url, string|callable $controllerAndAction, array|string $middleware = null)
 * @method static post(string $url, string|callable $controllerAndAction, array|string $middleware = null)
 * @method static delete(string $url, string|callable $controllerAndAction, array|string $middleware = null)
 * @method static update(string $url, string|callable $controllerAndAction, array|string $middleware = null)
 */
class Router implements RouterInterface
{
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
    protected array $currentGroupMiddleware = [];

    /**
     * Синглтон паттерн: метод для получения экземпляра
     *
     * @return Router|null
     */
    public static function getInstance(): ?Router
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Обрабатываем запрос
     *
     * @param ContainerInterface $container
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \Exception
     */
    public function dispatch(ContainerInterface $container)
    {
        $uri = $this->getUri();
        $method = $this->getMethod();

        foreach (static::getInstance()->routes[$method] as $routeUri => $routeInfo) {
            if (preg_match('#^' . $routeUri . '$#', $uri, $matches)) {
                // Middleware

                if (isset($routeInfo['middleware'])) {
                    foreach ($routeInfo['middleware'] as $middleware) {
                        if (is_array($middleware)) {
                            foreach ($middleware as $middlewareItem) {
                                $this->executeMiddleware($middlewareItem);
                            }
                        } else {
                            $this->executeMiddleware($middleware);
                        }
                    }
                }

                if (is_callable($routeInfo['controller'])) {
                    return call_user_func($routeInfo['controller']);
                }

                // Controller
                return $this->callAction($container, ...explode('@', $routeInfo['controller']));
            }
        }

        // Если маршрут не найден
        $this->sendNotFound();
    }

    /**
     * Регистрируем маршрут
     *
     * @param string $method
     * @param string $uri
     * @param $controller
     * @param null $middleware
     * @return void
     */
    public function addRoute(string $method, string $uri, $controller, $middleware = null): void
    {
        $uri = $this->prefix . '/' . trim($uri, '/');
        $uri = $uri !== '/' ? trim($uri, '/') : $uri;

        if ($middleware) {
            static::$instance->currentGroupMiddleware = array_merge(static::$instance->currentGroupMiddleware, (array)$middleware);
        }

        $data =  !empty(static::$instance->currentGroupMiddleware)
            ? ['controller' => $controller, 'middleware' => static::$instance->currentGroupMiddleware]
            : ['controller' => $controller];

        $this->routes[strtoupper($method)][$uri] = $data;
    }

    /**
     * Регистрирует группу маршрутов с опциональными префиксом и middleware.
     *
     * @param array $params Параметры группы, включая 'prefix' и 'middleware'.
     * @param callable $callback Функция, определяющая маршруты внутри группы.
     * @return Router
     */
    public static function group(array $params, callable $callback): Router
    {
        $instance = static::getInstance();

        // Сохраняем предыдущее состояние
        $previousPrefix = $instance->prefix;
        $previousMiddleware = $instance->currentGroupMiddleware;

        // Устанавливаем новый префикс, если он задан
        if (isset($params['prefix'])) {
            $instance->prefix .= '/' . trim($params['prefix'], '/');
        }

        // Добавляем middleware, если они заданы
        if (isset($params['middleware'])) {
            $instance->currentGroupMiddleware = array_merge($instance->currentGroupMiddleware, (array)$params['middleware']);
        }

        // Вызываем callback группы
        call_user_func($callback);

        // Восстанавливаем предыдущее состояние
        $instance->prefix = $previousPrefix;
        $instance->currentGroupMiddleware = $previousMiddleware;

        return $instance;
    }

    /**
     * Вызывает указанный метод контроллера, автоматически внедряя зависимости.
     *
     * @param ContainerInterface $container Контейнер зависимостей для автоматического внедрения.
     * @param string|callable $controller Имя класса контроллера.
     * @param string|null $action Имя метода в контроллере для вызова.
     * @return mixed Результат выполнения метода контроллера.
     * @throws ContainerExceptionInterface Если контейнер не может вернуть элемент.
     * @throws NotFoundExceptionInterface Если элемент не найден в контейнере.
     * @throws \ReflectionException
     */
    protected function callAction(ContainerInterface $container, string|callable $controller, string $action = null)
    {
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

    /**
     * @throws \Exception
     */
    private function executeMiddleware(string $middleware): void
    {
        if (!class_exists($middleware)) {
            throw new \Exception("Middleware class {$middleware} does not exist.");
        }

        $middlewareInstance = new $middleware();

        if (!$middlewareInstance instanceof MiddlewareInterface) {
            throw new \Exception("Middleware class {$middleware} must implement MiddlewareInterface.");
        }

        $middlewareInstance->handle();
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
    public static function __callStatic($method, $args)
    {
        call_user_func_array([static::getInstance(), 'addRoute'], [$method, ...$args]);

        return static::getInstance();
    }

    protected function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return void
     */
    protected function sendNotFound(): void
    {
        header("HTTP/2 404 Not Found");

        exit('404 Not Found');
    }
}