<?php

namespace App\Core;

use App\Core\Contracts\ApplicationInterface;
use App\Core\Contracts\RequestInterface;
use App\Core\Request\Request;
use App\Repository\Tasks\AuthorRepository;
use App\Repository\Tasks\AuthorRepositoryInterface;
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
        // Создаем новый экземпляр контейнера зависимостей
        $containerBuilder = new ContainerBuilder();

        // Конфигурируем контейнер
        $containerBuilder->addDefinitions([
            AuthorRepositoryInterface::class => \DI\autowire(AuthorRepository::class),
            RequestInterface::class => \DI\autowire(Request::class),
        ]);

        $this->container = $containerBuilder->build();
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