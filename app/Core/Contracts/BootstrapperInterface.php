<?php

namespace App\Core\Contracts;

use DI\Container;

interface BootstrapperInterface {
    public function boot(Container $container): void;
}
