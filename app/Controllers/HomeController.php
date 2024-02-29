<?php

namespace App\Controllers;

class HomeController {

    public function __invoke() {
        require __DIR__ . '/../../views/home.php';
    }
}