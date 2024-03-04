<!-- header.php -->
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
                    <a href="auth/login" class="<?= $activePage == 'login' ? 'text-indigo-600' : 'text-gray-800 hover:text-indigo-600'; ?> mx-4">Вход</a>
                </div>
            </div>
        </nav>
    </div>
</header>
<!-- /header.php -->