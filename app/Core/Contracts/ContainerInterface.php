<?php

namespace App\Core\Contracts;

interface ContainerInterface {
    public function bind($key, $value);
    public function get($key);
}
