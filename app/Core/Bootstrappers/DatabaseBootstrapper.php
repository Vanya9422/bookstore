<?php

namespace App\Core\Bootstrappers;

use App\Core\Contracts\BootstrapperInterface;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Illuminate\Database\Capsule\Manager;

class DatabaseBootstrapper implements BootstrapperInterface {

    /**
     * @throws DependencyException
     * @throws NotFoundException|\Exception
     */
    public function boot(Container $container): void
    {
        $connection = config('database.default');

        $connectionConfigs = config("database.connections.$connection");

        $capsule = new Manager();
        $capsule->addConnection([
            'driver' => $connectionConfigs['driver'],
            'host' => $connectionConfigs['host'],
            'database' => $connectionConfigs['database'],
            'username' => $connectionConfigs['username'],
            'password' => $connectionConfigs['password'],
            'charset' => $connectionConfigs['charset'],
            'collation' => $connectionConfigs['collation'],
            'prefix' => $connectionConfigs['prefix'],
        ]);

        $capsule->bootEloquent();
    }
}