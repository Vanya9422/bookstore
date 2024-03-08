<?php
$errorsMessages = session()->get('errors', []);
$successMessage = session()->get('success', []);
?>

<!-- Сообщения об ошибках -->
<?php if (!empty($errorsMessages)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative alert_message" role="alert">
        <strong class="font-bold">Ошибка!</strong>
        <span class="block sm:inline">
            <?php foreach ($errorsMessages as $field => $message): ?>
                <p><?= htmlspecialchars($message) ?></p>
            <?php endforeach; ?>
        </span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Закрыть</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.029a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.697z"/></svg>
        </span>
    </div>
<?php endif; ?>

<!-- Успешные сообщения -->
<?php if ($successMessage): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative alert_message" role="alert">
        <strong class="font-bold">Успех!</strong>
        <span class="block sm:inline"><?= htmlspecialchars($successMessage) ?></span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Закрыть</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.029a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.697z"/></svg>
        </span>
    </div>
<?php endif; ?>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const alerts = document.querySelectorAll('.alert_message');

        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.classList.add('opacity-0');

                setTimeout(() => alert.remove(), 1000);
            }, 5000);
        });
    });
</script>