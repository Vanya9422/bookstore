<?php
    include __DIR__ . '/../layouts/header.php';

    use App\Core\Application;
    use App\Core\Contracts\SessionManagerInterface;

    try {
        $session = Application::getContainer()->get(SessionManagerInterface::class);
        $errors = $session->get('errors', []);
        $old = $session->get('old', []);
        $successMessage = $session->get('success');
        // Предполагается, что 'errors' и 'old' уже извлечены выше, если это не так - извлекаем здесь
    } catch (\DI\DependencyException|\DI\NotFoundException $e) {
        // Обработка исключения
        $session = null; // Обеспечиваем, что $session будет объявлена даже в случае ошибки
    }
?>

<div class="flex items-center justify-center h-screen">
    <!-- Форма логина -->
    <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" action="/auth/login" method="POST">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Почта</label>
            <input class="shadow appearance-none border <?= isset($errors['email']) ? 'border-red-500' : '' ?> rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" type="email" placeholder="Почта" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
            <?php if (!empty($errors['email'])): ?>
                <p class="text-red-500"><?= htmlspecialchars($errors['email']['email'] ?? '') ?></p>
            <?php endif; ?>
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Пароль</label>
            <input class="shadow appearance-none border <?= isset($errors['password']) ? 'border-red-500' : '' ?> rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" type="password" placeholder="Пароль">
            <?php if (!empty($errors['password'])): ?>
                <p class="text-red-500 text-xs italic"><?= htmlspecialchars($errors['password']['password'] ?? '') ?></p>
            <?php endif; ?>
        </div>
        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Войти
            </button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>