<?php

namespace App\Core\Contracts;

use Psr\Container\ContainerInterface;

interface RouterInterface {
    public function dispatch(ContainerInterface $container);
}