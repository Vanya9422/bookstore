<?php

namespace App\Core;

use App\Core\Contracts\ApplicationInterface;
use App\Core\Contracts\DatabaseInterface;
use App\Core\Database\Database;
use App\Repository\Tasks\AuthorRepository;
use App\Repository\Tasks\AuthorRepositoryInterface;
use DI\Container;
use DI\ContainerBuilder;
use App\Core\Contracts\BootstrapperInterface;

class Application implements ApplicationInterface {

    /**
     * @var Container
     */
    private static Container $container;

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
            DatabaseInterface::class => function () {
                $connection = config('database.default');
                $connectionConfigs = config("database.connections.$connection");
                return new Database($connectionConfigs);
            },
        ]);

        static::$container = $containerBuilder->build();
    }

    public function addBootstrapper(BootstrapperInterface $bootstrapper): void {
        $this->bootstrappers[] = $bootstrapper;
    }

    public function run(): void {
        foreach ($this->bootstrappers as $bootstrapper) {
            $bootstrapper->boot(static::$container);
        }
    }

    public static function isContainerInitialized(): bool {
        return isset(self::$container);
    }

    public static function getContainer(): ?Container {
        if (self::isContainerInitialized()) {
            return self::$container;
        }

        return null;
    }
}