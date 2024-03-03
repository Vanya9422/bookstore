<?php

namespace App\Core;

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;

abstract class BaseMigration extends AbstractMigration
{
    /**
     * @throws \Exception
     */
    public function init(): void
    {
        $connection = config('database.default');

        $connectionConfigs = config("database.connections.$connection");

        $capsule = new Capsule();
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

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}