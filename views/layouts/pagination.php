<div class="mt-6 mb-5">
    <!-- Пагинация -->
    <nav class="flex justify-center">
        <?php
        $start = max(1, $list['current_page'] - 2);
        $end = min($list['total_pages'], $list['current_page'] + 2);
        if ($start > 1) : ?>
            <a href="?page=1" class="mx-1 px-3 py-2 bg-white text-gray-700 hover:bg-indigo-500 hover:text-white rounded-md text-sm font-medium">1</a>
            <?php if ($start > 2) : ?>
                <span class="mx-1 px-3 py-2 text-gray-700 text-sm font-medium">...</span>
            <?php endif; ?>
        <?php endif;

        for ($i = $start; $i <= $end; $i++) : ?>
            <a href="?page=<?= $i ?>" class="mx-1 px-3 py-2 bg-white text-gray-700 hover:bg-indigo-500 hover:text-white rounded-md text-sm font-medium <?= $i == $list['current_page'] ? 'bg-indigo-500 text-white' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor;

        if ($end < $list['total_pages']) : ?>
            <?php if ($end < $list['total_pages'] - 1) : ?>
                <span class="mx-1 px-3 py-2 text-gray-700 text-sm font-medium">...</span>
            <?php endif; ?>
            <a href="?page=<?= $list['total_pages'] ?>" class="mx-1 px-3 py-2 bg-white text-gray-700 hover:bg-indigo-500 hover:text-white rounded-md text-sm font-medium"><?= $list['total_pages'] ?></a>
        <?php endif; ?>
    </nav>
</div>