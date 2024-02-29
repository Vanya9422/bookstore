<?php

namespace App\Core\Contracts;

interface RouterInterface {
    public static function get($uri, $controller);
    public static function post($uri, $controller);
    public function dispatch();
}