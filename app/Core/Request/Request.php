<?php

namespace App\Core\Request;

use App\Core\Contracts\RequestInterface;

final class Request implements RequestInterface
{
    private array $query;
    private array $request;

    public function __construct() {
        $this->query = $_GET;
        $this->request = $_POST;
    }

    public function get($key, $default = null) {
        return $this->query[$key] ?? $default;
    }

    public function request($key, $default = null)
    {
        return $this->request[$key] ?? $default;
    }
}