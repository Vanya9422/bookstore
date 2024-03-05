<?php

namespace App\Core\Database;

use App\Core\Contracts\DatabaseInterface;
use PDOException;
use PDO;

class Database implements DatabaseInterface {
    private $config;

    public function __construct(array $config) {
        $this->config = $config;
    }

    public function connect() {
        try {
            $dsn = $this->config['driver'] .
                ':host=' . $this->config['host'] .
                ';dbname=' . $this->config['database'] .
                ';port=' . $this->config['port'] .
                ';charset=' . $this->config['charset'];

            $options = $this->config['options'] ?? [];

            return new PDO($dsn, $this->config['username'], $this->config['password'], $options);
        } catch (PDOException $e) {
            // Логирование или другие действия обработки исключений
            throw new PDOException("Connection error: " . $e->getMessage(), (int)$e->getCode());
        }
    }
}