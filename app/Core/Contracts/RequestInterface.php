<?php

namespace App\Core\Contracts;

interface RequestInterface
{
    public function get($key, $default = null);
    public function request($key, $default = null);
}