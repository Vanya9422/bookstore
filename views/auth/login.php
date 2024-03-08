<?php
include __DIR__ . '/../layouts/header.php';
[$errors, $old] = formErrors();
?>

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-xl p-6 bg-white rounded-md shadow-md">
        <div class="mb-4">
            <?php include __DIR__ . '/../layouts/session_messages.php'; ?>
        </div>

        <form action="/auth/login" method="POST">
            <div class="mb-6">
                <label class="block mb-2 text-sm font-bold text-gray-700" for="email">Почта</label>
                <input class="shadow appearance-none border <?= isset($errors['email']) ? 'border-red-500' : '' ?> rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" type="email" placeholder="Почта" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                <?php if (!empty($errors['email'])): ?>
                    <div class="mt-2 text-xs italic text-red-500">
                        <?php foreach ($errors['email'] as $error): ?>
                            <p><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-6">
                <label class="block mb-2 text-sm font-bold text-gray-700" for="password">Пароль</label>
                <input class="shadow appearance-none border <?= isset($errors['password']) ? 'border-red-500' : '' ?> rounded w-full py-3 px-4 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" type="password" placeholder="Пароль" required>
                <?php if (!empty($errors['password'])): ?>
                    <div class="mt-2 text-xs italic text-red-500">
                        <?php foreach ($errors['password'] as $error): ?>
                            <p><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="flex items-center justify-between">
                <button class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Войти
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
