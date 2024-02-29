<?php

namespace App\Core\Container;

use App\Core\Contracts\ContainerInterface;

class Container implements ContainerInterface
{
    protected $bindings = [];

    public function bind($key, $value)
    {
        $this->bindings[$key] = $value;
    }

    /**
     * @throws \Exception
     */
    public function get($key)
    {
        if (!isset($this->bindings[$key])) {
            throw new \Exception("No binding found for {$key}");
        }
        return $this->bindings[$key] instanceof \Closure ? $this->bindings[$key]() : $this->bindings[$key];
    }
}