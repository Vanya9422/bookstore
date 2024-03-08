<!-- header.php -->

<?php
    $sessionManager = new \App\Core\Session\SessionManager();

    $AuthUser = $sessionManager->authUser();

    $isLoggedIn = $sessionManager->authCheck();

    if(!isset($activePage)) $activePage = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Заголовок вашего сайта</title>
    <!-- Ссылка на Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>

<header class="bg-gray-800 text-white p-4">
    <div class="bg-white shadow-md fixed top-0 left-0 right-0 z-50">
        <nav class="container mx-auto px-6 py-3">
            <div class="flex items-center justify-between">
                <a href="/" class="text-gray-800 text-xl font-bold">Моя библиотека</a>
                <div class="flex items-center">
                    <a href="/" class="<?= $activePage == 'home' ? 'text-indigo-600' : 'text-gray-800 hover:text-indigo-600'; ?> mx-4">Главная</a>
                    <?php if ($isLoggedIn): ?>
                        <a href="/admin/dashboard" class="<?= $activePage == 'dashboard' ? 'text-indigo-600' : 'text-gray-800 hover:text-indigo-600'; ?> mx-4">Дашборд</a>
                        <a href="/admin/books" class="<?= $activePage == 'books' ? 'text-indigo-600' : 'text-gray-800 hover:text-indigo-600'; ?> mx-4">Лист Книг</a>
                        <a href="/admin/books/create" class="<?= $activePage == 'create_books' ? 'text-indigo-600' : 'text-gray-800 hover:text-indigo-600'; ?> mx-4">Добавить Книгу</a>
                        <a href="/admin/authors" class="<?= $activePage == 'authors' ? 'text-indigo-600' : 'text-gray-800 hover:text-indigo-600'; ?> mx-4">Список Авторов</a>
                        <a href="/admin/authors/create" class="<?= $activePage == 'create_authors' ? 'text-indigo-600' : 'text-gray-800 hover:text-indigo-600'; ?> mx-4">Добавить Автора</a>
                        <form action="/auth/logout" method="POST">
                            <button type="submit" class="text-gray-800 hover:text-indigo-700 ml-20"><b>Выход</b></button>
                        </form>
                        <span class="mx-4 text-black"> <b><?= htmlspecialchars($AuthUser->name) ?></b> </span>
                    <?php else: ?>
                        <a href="/auth/login" class="<?= $activePage == 'login' ? 'text-indigo-600' : 'text-gray-800 hover:text-indigo-600'; ?> mx-4">Вход</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </div>
</header>
<!-- /header.php -->
