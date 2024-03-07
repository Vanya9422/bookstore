<?php

include __DIR__ . '/../layouts/header.php';

?>

<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold text-gray-900 pt-6">Дашборд администратора</h1>
    <div class="mt-5">
        <!-- Статистика сайта -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-5">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Статистика сайта
                </h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Количество пользователей
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            120
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Количество статей
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            45
                        </dd>
                    </div>
                    <!-- Добавьте дополнительные строки статистики по мере необходимости -->
                </dl>
            </div>
        </div>

        <!-- Последние зарегистрированные пользователи или другие данные -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Последние зарегистрированные пользователи
                </h3>
                <!-- Здесь может быть таблица или список последних пользователей -->
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>