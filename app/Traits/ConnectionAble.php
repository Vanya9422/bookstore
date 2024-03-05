<?php

namespace App\Traits;

use App\Core\Database\Database;

trait ConnectionAble
{
    /**
     * @throws \Exception
     */
    public function getDatabaseConnection()
    {
        $connection = config('database.default');

        $connectionConfigs = config("database.connections.$connection");

        $database = new Database($connectionConfigs);

        return $database->connect();
    }
}