<?php

include __DIR__ . '/../../layouts/header.php';
[$errors, $old] = formErrors();
?>

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-xl p-6 bg-white rounded-md shadow-md">
        <div class="mb-4">
            <?php include __DIR__ . '/../../layouts/session_messages.php'; ?>
        </div>

        <form action="/admin/authors/store" method="POST">
            <div class="mb-6">
                <label class="block mb-2 text-sm font-bold text-gray-700" for="name">Название Автора</label>
                <input class="shadow appearance-none border <?= isset($errors['name']) ? 'border-red-500' : '' ?> rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="name"
                       name="name"
                       type="text"
                       placeholder="Название Автора"
                       value="<?= htmlspecialchars($old['name'] ?? '') ?>" required
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
                    Добавить
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
