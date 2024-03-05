<?php

namespace App\Core\Contracts;

interface SessionManagerInterface
{
    public function get($key, $default = null);
    public function set($key, $value): void;
    public function delete($key): void;
    public function close(): void;
    public function all(): array;
    public function clear(): void;
}