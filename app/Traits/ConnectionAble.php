<?php

namespace App\Traits;

use App\Core\Application;
use App\Core\Contracts\DatabaseInterface;

trait ConnectionAble
{
    /**
     * @throws \Exception
     */
    public function getDatabaseConnection() {
        $container = Application::getContainer();

        if (!Application::getContainer()) {
            Application::init();
            $container = Application::getContainer();
        }

        $database = $container->get(DatabaseInterface::class);

        return $database->connect();
    }
}