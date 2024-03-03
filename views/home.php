<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список авторов и книг</title>
</head>
<body>
<h1>Список авторов и их книг</h1>
<ul>
    <?php foreach ($authors as $author): ?>
        <li>
            <h2><?= $author->name ?></h2>
            <ul>
                <?php foreach ($author->books as $book): ?>
                    <li>
                        <strong><?= $book->title ?></strong> - <?= $book->description ?> (<?= $book->published_year ?>)
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
    <?php endforeach; ?>
</ul>

<!-- Добавление ссылок для пагинации -->
<div>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>
</body>
</html>