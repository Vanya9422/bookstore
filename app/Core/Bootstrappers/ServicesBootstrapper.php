<?php

namespace App\Core\Bootstrappers;

use App\Core\Contracts\BootstrapperInterface;
use DI\Container;
use App\Core\Contracts\RouterInterface;
use App\Core\Route\Router;
use function DI\create;

class ServicesBootstrapper implements BootstrapperInterface {
    public function boot(Container $container): void {
        $container->set(RouterInterface::class, create(Router::class));
    }
}