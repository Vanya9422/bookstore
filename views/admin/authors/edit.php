<?php
include __DIR__ . '/../../layouts/header.php';
[$errors, $old] = formErrors();
?>

    <div class="flex items-center justify-center h-screen bg-gray-100">
        <div class="w-full max-w-2xl p-6 bg-white rounded-md shadow-md">

            <div class="mb-4">
                <?php include __DIR__ . '/../../layouts/session_messages.php'; ?>
            </div>

            <form action="/admin/authors/<?= htmlspecialchars($author->id) ?>/update" method="POST">
                <input type="hidden" name="_method" value="PUT">

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-bold text-gray-700" for="name">Имя Автора</label>
                    <input class="shadow appearance-none border <?= isset($errors['name']) ? 'border-red-500' : '' ?> rounded w-full py-3 px-4 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                           id="name"
                           name="name"
                           type="text"
                           placeholder="Имя Автора"
                           value="<?= htmlspecialchars($old['name'] ?? $author->name) ?>" required
                    >
                    <?php if (!empty($errors['name'])): ?>
                        <div class="mt-2 text-xs italic text-red-500">
                            <?php foreach ($errors['name'] as $error): ?>
                                <p><?= htmlspecialchars($error) ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex items-center justify-between">
                    <button class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Обновить
                    </button>
                </div>
            </form>

            <div class="mt-8">
                <h2 class="mb-4 text-xl font-semibold text-gray-700">Книги автора</h2>
                <ul>
                    <?php foreach ($author->books as $book): ?>
                        <li class="mb-4 p-4 bg-gray-100 rounded-md flex justify-between items-center">
                            <?= htmlspecialchars($book['title']) ?>
                            <form action="/admin/books/<?= htmlspecialchars($book['id']) ?>/delete" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту книгу?');">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="text-white bg-red-500 hover:bg-red-700 font-bold py-2 px-4 rounded transition duration-300 ease-in-out focus:outline-none">
                                    Удалить
                                </button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>