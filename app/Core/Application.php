<?php

namespace App\Core;

use App\Core\Contracts\ApplicationInterface;
use DI\Container;
use DI\ContainerBuilder;
use App\Core\Contracts\BootstrapperInterface;

class Application implements ApplicationInterface {

    /**
     * @var Container
     */
    private Container $container;

    private array $bootstrappers = [];

    /**
     * @throws \Exception
     */
    public function __construct() {
        $this->container = (new ContainerBuilder)->build();
    }

    public function addBootstrapper(BootstrapperInterface $bootstrapper): void {
        $this->bootstrappers[] = $bootstrapper;
    }

    public function run(): void {
        foreach ($this->bootstrappers as $bootstrapper) {
            $bootstrapper->boot($this->container);
        }
    }
}