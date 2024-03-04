<?php

namespace App\Core\Bootstrappers;

use App\Core\Contracts\BootstrapperInterface;
use App\Core\Contracts\RouterInterface;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;

class RouteBootstrapper implements BootstrapperInterface {

    /**
     * @param Container $container
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function boot(Container $container): void {
        $this->setupRoutes();

        try {
            $this->dispatchRoutes($container);
        } catch (DependencyException|NotFoundException $e) {
            throw $e;
        }
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    private function dispatchRoutes(Container $container): void {
        $router = $container->get(RouterInterface::class);
        $router->dispatch($container);
    }

    private function setupRoutes(): void {
        // Подключение файла с маршрутами
        require __DIR__ . '/../../../routes/web.php';
    }
}