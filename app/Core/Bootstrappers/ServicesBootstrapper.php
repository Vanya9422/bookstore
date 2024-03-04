<?php

namespace App\Core\Bootstrappers;

use App\Core\Contracts\BootstrapperInterface;
use DI\Container;
use App\Core\Contracts\RouterInterface;
use App\Core\Route\Router;

class ServicesBootstrapper implements BootstrapperInterface {
    public function boot(Container $container): void {
        $container->set(RouterInterface::class, \DI\create(Router::class));
    }
}