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
 * @method static put(string $url, string|callable $controllerAndAction, array|string $middleware = null)
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
     * Обрабатывает запрос и определяет маршрут для исполнения.
     *
     * @param ContainerInterface $container
     * @return mixed|void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function dispatch(ContainerInterface $container)
    {
        $uri = $this->getUri();
        $method = $this->getMethod();

        foreach (static::getInstance()->routes[$method] as $routeUri => $routeInfo) {
            $routePattern = preg_replace('#\{([a-z_]+)\}#', '(?<$1>[^/]+)', $routeUri);
            if (preg_match('#^' . $routePattern . '$#', $uri, $matches)) {

                // Извлекаем и удаляем полный патч, оставляя только именованные параметры
                array_shift($matches);

                // Middleware
                if (isset($routeInfo['middleware'])) {
                    foreach ($routeInfo['middleware'] as $middleware) {
                        if (is_array($middleware)) {
                            foreach ($middleware as $middlewareItem) {
                                $this->executeMiddleware($middlewareItem, $container);
                            }
                        } else {
                            $this->executeMiddleware($middleware, $container);
                        }
                    }
                }

                if (is_callable($routeInfo['controller'])) {
                    // Для вызываемых контроллеров передаем параметры из URL
                    return call_user_func_array($routeInfo['controller'], $matches);
                }

                $controllerAction = explode('@', $routeInfo['controller']);
                if (count($controllerAction) === 1) {
                    return $this->callAction($container, $controllerAction[0], '__invoke', $matches);
                } else {
                    return $this->callAction($container, $controllerAction[0], $controllerAction[1], $matches);
                }
            }
        }

        // Если маршрут не найден
        $this->sendNotFound();
    }

    /**
     * Вызывает указанный метод контроллера, автоматически внедряя зависимости и передавая динамические параметры из URL.
     *
     * Этот метод автоматически инжектирует зависимости, определенные в методе контроллера, используя контейнер зависимостей.
     * Кроме того, он передает динамические параметры, извлеченные из URL (например, {id} или {author_id}), непосредственно в метод контроллера.
     *
     * @param ContainerInterface $container Контейнер зависимостей для автоматического внедрения.
     * @param string $controller Имя класса контроллера или вызываемый объект.
     * @param string|null $action Имя метода в контроллере для вызова. Если не указано, используется метод __invoke.
     * @param array $routeParams Ассоциативный массив параметров из URL, которые должны быть переданы в метод контроллера.
     *
     * @return mixed Результат выполнения метода контроллера.
     *
     * @throws ContainerExceptionInterface Если контейнер не может вернуть элемент.
     * @throws NotFoundExceptionInterface Если элемент не найден в контейнере.
     * @throws \Exception Если класс контроллера не найден, метод контроллера не существует или не удается разрешить параметр метода.
     * @throws \ReflectionException Если произошла ошибка при использовании Reflection.
     */
    protected function callAction(ContainerInterface $container, string $controller, ?string $action, array $routeParams = []): mixed {

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
            $parameterName = $parameter->getName();

            // Проверяем, передан ли параметр из маршрута
            if (array_key_exists($parameterName, $routeParams)) {
                $args[] = $routeParams[$parameterName];
            } elseif ($container->has($parameterType->getName())) {
                $args[] = $container->get($parameterType->getName());
            } else {
                throw new \Exception("Cannot resolve the parameter `{$parameterName}` in method `{$methodName}` of controller `{$controller}`.");
            }
        }

        // Вызываем метод контроллера с подготовленными аргументами
        return $method->invokeArgs($controllerInstance, $args);
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

    /**
     * Извлекает HTTP метод из глобальной переменной $_SERVER или из переопределенного значения в $_POST['_method'].
     *
     * @return string Возвращаемый HTTP метод.
     */
    protected function getMethod(): string
    {
        $method = $_SERVER['REQUEST_METHOD'];

        // Поддержка переопределения метода через скрытое поле _method для форм.
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = $_POST['_method'];
        }

        return strtoupper($method);
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