<?php include __DIR__ . '/../layouts/header.php'; ?>

<main class="container mx-auto px-4 sm:px-8 max-w-3xl pt-16 mb-10">
    <div class="py-8">
        <div class="flex flex-wrap mb-6 justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-700">Авторы и их книги</h1>
            <div class="text-end">
                <p class="text-xs text-gray-500">Страница <?= $authors['current_page'] ?> из <?= $authors['total_pages'] ?></p>
            </div>
        </div>

        <div class="mt-6">
            <!-- Пагинация -->
            <nav class="flex justify-center pb-2">
                <?php for ($i = 1; $i <= $authors['total_pages']; $i++): ?>
                    <a href="?page=<?= $i ?>" class="mx-1 px-3 py-2 bg-white text-gray-700 hover:bg-indigo-500 hover:text-white rounded-md text-sm font-medium <?= $i == $authors['current_page'] ? 'bg-indigo-500 text-white' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </nav>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <ul class="divide-y divide-gray-200">
                <?php foreach ($authors['data'] as $author): ?>
                    <li class="p-4 hover:bg-gray-50 cursor-pointer">
                        <strong class="text-lg text-indigo-600"><?= htmlspecialchars($author['name']) ?></strong>
                        <ul class="mt-2 space-y-1 text-gray-500">
                            <?php foreach ($author['books'] as $book): ?>
                                <li class="text-sm"><?= htmlspecialchars($book['title']) ?> (<?= $book['published_year'] ?>)</li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="mt-6">
            <!-- Пагинация -->
            <nav class="flex justify-center">
                <?php for ($i = 1; $i <= $authors['total_pages']; $i++): ?>
                    <a href="?page=<?= $i ?>" class="mx-1 px-3 py-2 bg-white text-gray-700 hover:bg-indigo-500 hover:text-white rounded-md text-sm font-medium <?= $i == $authors['current_page'] ? 'bg-indigo-500 text-white' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </nav>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>