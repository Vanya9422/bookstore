<?php

namespace App\Core\Request;

use App\Core\Contracts\RequestInterface;

class Request implements RequestInterface
{
    private array $query;
    private array $request;

    public function __construct() {
        $this->query = $_GET;
        $this->request = $_POST;
    }

    public function get($key, $default = null) {
        return $this->all()[$key] ?? $default;
    }

    public function has($key): bool {
        return isset($this->query[$key]) || isset($this->request[$key]);
    }

    public function all(): array {
        return array_merge($this->request, $this->query);
    }
}