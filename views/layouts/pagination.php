<div class="mt-6 mb-5">
    <!-- Пагинация -->
    <nav class="flex justify-center">
        <?php
        $start = max(1, $paginator->getCurrentPage() - 2);
        $end = min($paginator->getTotalPages(), $paginator->getCurrentPage() + 2);
        if ($start > 1) : ?>
            <a href="?page=1" class="mx-1 px-3 py-2 bg-white text-gray-700 hover:bg-indigo-500 hover:text-white rounded-md text-sm font-medium">1</a>
            <?php if ($start > 2) : ?>
                <span class="mx-1 px-3 py-2 text-gray-700 text-sm font-medium">...</span>
            <?php endif; ?>
        <?php endif;

        for ($i = $start; $i <= $end; $i++) : ?>
            <a href="?page=<?= $i ?>" class="mx-1 px-3 py-2 bg-white text-gray-700 hover:bg-indigo-500 hover:text-white rounded-md text-sm font-medium <?= $i == $paginator->getCurrentPage() ? 'bg-indigo-500 text-white' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor;

        if ($end < $paginator->getTotalPages()) : ?>
            <?php if ($end < $paginator->getTotalPages() - 1) : ?>
                <span class="mx-1 px-3 py-2 text-gray-700 text-sm font-medium">...</span>
            <?php endif; ?>
            <a href="?page=<?= $paginator->getTotalPages() ?>" class="mx-1 px-3 py-2 bg-white text-gray-700 hover:bg-indigo-500 hover:text-white rounded-md text-sm font-medium"><?= $paginator->getTotalPages() ?></a>
        <?php endif; ?>
    </nav>
</div>