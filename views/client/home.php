<?php include __DIR__ . '/../layouts/header.php'; ?>

<main class="container mx-auto px-4 sm:px-8 max-w-3xl pt-16 mb-10">
    <div class="py-8">
        <div class="flex flex-wrap mb-6 justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-700">Авторы и их книги</h1>
            <div class="text-end">
                <p class="text-xs text-gray-500">Страница <?= $list['current_page'] ?> из <?= $list['total_pages'] ?></p>
            </div>
        </div>

        <?php include __DIR__ . '/../layouts/session_messages.php'; ?>

        <?php include __DIR__ . '/../layouts/pagination.php'; ?>

        <!-- Проверка на пустой список авторов -->
        <?php if (empty($list['data'])): ?>
            <p class="text-gray-700 text-center">Авторы не найдены.</p>
        <?php else: ?>
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <ul class="divide-y divide-gray-200">
                    <?php foreach ($list['data'] as $author): ?>
                        <li class="p-4 hover:bg-gray-50 cursor-pointer">
                            <strong class="text-lg text-indigo-600"><?= htmlspecialchars($author['name']) ?></strong>
                            <ul class="mt-2 space-y-1 text-gray-500">
                                <?php foreach ($author['books'] as $book): ?> <!-- Обратите внимание на индексацию по ID автора -->
                                    <li class="text-sm"><?= htmlspecialchars($book['title']) ?> (<?= $book['published_year'] ?>)</li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php include __DIR__ . '/../layouts/pagination.php'; ?>
    </div>
</main>
<?php include __DIR__ . '/../layouts/footer.php'; ?>