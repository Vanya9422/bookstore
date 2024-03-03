<?php

namespace App\Controllers;

use App\Models\Author;

class HomeController {

    public function __invoke() {
        // Получение номера текущей страницы
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        // Количество записей на странице
        $perPage = 10;

        // Вычисление смещения (OFFSET)
        $offset = ($currentPage - 1) * $perPage;

        // Получение авторов с их книгами с пагинацией
        $authors = Author::with('books')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        // Получение общего количества авторов
        $totalAuthors = Author::count();

        // Рассчет количества страниц
        $totalPages = ceil($totalAuthors / $perPage);

        // Подключение представления и передача данных
        require __DIR__ . '/../../views/home.php';
    }
}