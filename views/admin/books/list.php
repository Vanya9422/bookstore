<?php include __DIR__ . '/../../layouts/header.php'; ?>

<main class="container mx-auto px-4 sm:px-8 max-w-3xl pt-16 mb-10">
    <div class="py-8">
        <div class="flex flex-wrap mb-6 justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-700">Книги и их авторы</h1>
        </div>

        <div class="mt-6">
            <?php include __DIR__ . '/../../layouts/session_messages.php'; ?>

            <?php include __DIR__ . '/../../layouts/pagination.php'; ?>

            <!-- Вывод списка книг -->
            <?php if (empty($list['data'])): ?>
                <p class="text-gray-700 text-center">Книги не найдены.</p>
            <?php else: ?>
                <table class="min-w-full divide-y divide-gray-200 mt-10">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Название книги
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Автор
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Год публикации
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Создана
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Обновлено
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($list['data'] as $book): ?>
                        <tr class="hover:bg-gray-50 cursor-pointer">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <strong class="text-indigo-600"><?= htmlspecialchars($book['title']) ?></strong>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?= htmlspecialchars($book['name']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?= htmlspecialchars($book['published_year']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?= htmlspecialchars($book['created_at']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($book['created_at'] === $book['updated_at']): ?>
                                    Не Обновлено
                                <?php else: ?>
                                    <?= timeElapsedString($book['updated_at']) ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <?php include __DIR__ . '/../../layouts/pagination.php'; ?>

        </div>
    </div>
</main>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
