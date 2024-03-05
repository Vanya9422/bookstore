<?php
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

<?php if ($session): ?>
    <?php
    $errors = $session->get('errors', []);
    $successMessage = $session->get('success');
    ?>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $field => $errorDetails): ?>
                <?php foreach ($errorDetails as $rule => $message): ?>
                    <!-- Теперь корректно обрабатываем массив сообщений об ошибках -->
                    <p class="text-red-500"><?= htmlspecialchars($message) ?></p>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($successMessage): ?>
        <div class="success">
            <p class="text-green-500"><?= htmlspecialchars($successMessage) ?></p>
        </div>
    <?php endif; ?>
<?php endif; ?>
