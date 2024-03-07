<?php
    try {
        $session = new \App\Core\Session\SessionManager();
        $errors = $session->get('errors', []);
        $old = $session->get('old', []);
        $successMessage = $session->get('success');
    } catch (\DI\DependencyException|\DI\NotFoundException $e) {
        $session = null;
    }
?>

<?php if ($session): ?>
    <?php
    $errors = $session->get('errors', []);
    $successMessage = $session->get('success');
    ?>

    <?php if (!empty($errors)): ?>
        <div class="errors p-2">
            <?php foreach ($errors as $field => $message): ?>
                <p class="text-red-500"><?= htmlspecialchars($message) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($successMessage): ?>
        <div class="success">
            <p class="text-green-500"><?= htmlspecialchars($successMessage) ?></p>
        </div>
    <?php endif; ?>
<?php endif; ?>
