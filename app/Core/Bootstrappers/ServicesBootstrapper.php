<?php

namespace App\Core\Bootstrappers;

use App\Core\Contracts\BootstrapperInterface;
use App\Core\Contracts\SessionManagerInterface;
use App\Core\Session\SessionManager;
use DI\Container;
use App\Core\Contracts\RouterInterface;
use App\Core\Route\Router;

class ServicesBootstrapper implements BootstrapperInterface {
    public function boot(Container $container): void {
        $container->set(RouterInterface::class, \DI\create(Router::class));
        $container->set(SessionManagerInterface::class, \DI\create(SessionManager::class));
    }
}