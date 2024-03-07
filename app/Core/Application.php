<?php

namespace App\Core;

use App\Core\Contracts\ApplicationInterface;
use App\Core\Contracts\BootstrapperInterface;
use App\Core\Contracts\DatabaseInterface;
use App\Core\Database\Database;
use App\Repository\Client\AuthorRepository;
use App\Repository\Client\AuthorRepositoryInterface;
use App\Repository\Client\BookRepository;
use App\Repository\Client\BookRepositoryInterface;
use App\Repository\Client\UserRepository;
use App\Repository\Client\UserRepositoryInterface;
use DI\Container;
use DI\ContainerBuilder;

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
        self::init();
    }

    /**
     * Создаем новый экземпляр контейнера зависимостей
     *
     * @return void
     * @throws \Exception
     */
    public static function init(): void
    {
        $containerBuilder = new ContainerBuilder();

        // Конфигурируем контейнер
        $containerBuilder->addDefinitions([
            DatabaseInterface::class => function () {
                $connection = config('database.default');
                $connectionConfigs = config("database.connections.$connection");
                return new Database($connectionConfigs);
            },
            AuthorRepositoryInterface::class => \DI\create(AuthorRepository::class),
            UserRepositoryInterface::class => \DI\create(UserRepository::class),
            BookRepositoryInterface::class => \DI\create(BookRepository::class)
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

    public static function getContainer(): ?Container {
        if (isset(self::$container)) {
            return self::$container;
        }

        return null;
    }
}