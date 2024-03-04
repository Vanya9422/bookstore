<!-- home.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home Page</title>
</head>
<body>
<h1>Авторы и их книги</h1>
<ul>
    <?php foreach ($authors['data'] as $author): ?>
        <li>
            <strong><?= htmlspecialchars($author['name']) ?></strong>
            <ul>
                <?php foreach ($author['books'] as $book): ?>
                    <li><?= htmlspecialchars($book['title']) ?> (<?= $book['published_year'] ?>)</li>
                <?php endforeach; ?>
            </ul>
        </li>
    <?php endforeach; ?>
</ul>

<div>
    Страница <?= $authors['current_page'] ?> из <?= $authors['total_pages'] ?>
</div>

<!-- Пагинация -->
<nav>
    <?php for ($i = 1; $i <= $authors['total_pages']; $i++): ?>
        <a href="?page=<?= $i ?>"><?= $i ?></a>
    <?php endfor; ?>
</nav>
</body>
</html>