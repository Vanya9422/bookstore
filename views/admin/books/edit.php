<?php
include __DIR__ . '/../../layouts/header.php';
[$errors, $old] = formErrors();
?>

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-xl p-6 bg-white rounded-md shadow-md">

        <div class="flex flex-wrap mb-6 justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-700">Книга - <?= htmlspecialchars($book->title) ?></h1>
        </div>

        <div class="w-100 mt-2 mb-2">
            <?php include __DIR__ . '/../../layouts/session_messages.php'; ?>
        </div>

        <form action="/admin/books/<?= htmlspecialchars($book->id) ?>/update" method="POST">
            <input type="hidden" name="_method" value="PUT">
            <div class="mb-6">
                <label class="block mb-2 text-sm font-bold text-gray-700" for="title">Название Книги</label>
                <input class="shadow appearance-none border <?= isset($errors['title']) ? 'border-red-500' : '' ?> rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="title"
                       name="title"
                       type="text"
                       placeholder="Название Книги"
                       value="<?= htmlspecialchars($old['title'] ?? $book->title) ?>" required
                >
                <?php if (!empty($errors['title'])): ?>
                    <div class="mt-2 text-xs italic text-red-500">
                        <?php foreach ($errors['title'] as $error): ?>
                            <p><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-6">
                <label class="block mb-2 text-sm font-bold text-gray-700" for="author_id">Автор</label>
                <select class="shadow appearance-none border <?= isset($errors['author_id']) ? 'border-red-500' : '' ?> rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="author_id"
                        name="author_id" required>
                    <option value="">Выберите автора</option>
                    <?php foreach ($authors as $author): ?>
                        <option value="<?= $author['id'] ?>" <?= $author['id'] == $book->author_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($author['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['author_id'])): ?>
                    <div class="mt-2 text-xs italic text-red-500">
                        <?php foreach ($errors['author_id'] as $error): ?>
                            <p><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-6">
                <label class="block mb-2 text-sm font-bold text-gray-700" for="published_year">Год публикации</label>
                <input class="shadow appearance-none border <?= isset($errors['published_year']) ? 'border-red-500' : '' ?> rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="published_year"
                       name="published_year"
                       type="number"
                       placeholder="Год публикации"
                       value="<?= htmlspecialchars($old['published_year'] ?? $book->published_year) ?>">
                <?php if (!empty($errors['published_year'])): ?>
                    <div class="mt-2 text-xs italic text-red-500">
                        <?php foreach ($errors['published_year'] as $error): ?>
                            <p><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-6">
                <label class="block mb-2 text-sm font-bold text-gray-700" for="description">Описание Книги</label>
                <textarea class="shadow appearance-none border <?= isset($errors['description']) ? 'border-red-500' : '' ?> rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                          id="description"
                          name="description"
                          rows="4"
                          placeholder="Описание книги"><?= htmlspecialchars($old['description'] ?? $book->description) ?></textarea>
                <?php if (!empty($errors['description'])): ?>
                    <div class="mt-2 text-xs italic text-red-500">
                        <?php foreach ($errors['description'] as $error): ?>
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
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
