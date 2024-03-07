<?php

use Phinx\Seed\AbstractSeed;

class AuthorsAndBooksSeeder extends AbstractSeed
{

    use \App\Traits\ConnectionAble;

    /**
     * @throws Exception
     */
    public function run(): void
    {
        // Получаем объект соединения с базой данных
        $db = $this->getDatabaseConnection();

        // Массив данных об авторах и книгах
        $authorsData = [
            [
                'name' => 'Лев Толстой',
                'books' => [
                    ['title' => 'Война и мир', 'description' => 'Описание книги Война и мир', 'published_year' => 1869],
                    ['title' => 'Анна Каренина', 'description' => 'Описание книги Анна Каренина', 'published_year' => 1877]
                ]
            ]
        ];

        // Добавление данных для остальных 19 авторов
        for ($i = 1; $i <= 60; $i++) {
            $authorsData[] = [
                'name' => 'Автор ' . $i,
                'books' => []
            ];
            $numberOfBooks = rand(5, 10); // Случайное количество книг от 10 до 20
            for ($j = 1; $j <= $numberOfBooks; $j++) {
                $authorsData[$i]['books'][] = [
                    'title' => 'Книга ' . $j . ' от автора ' . $i,
                    'description' => 'Описание книги ' . $j . ' от автора ' . $i,
                    'published_year' => rand(1800, 2024) // Случайный год издания
                ];
            }
        }

        $Author = new \App\Models\Author();
        $bookClass = new \App\Models\Book();
        $Author->setConnection($db);
        $bookClass->setConnection($db);

        foreach ($authorsData as $authorData) {
            $author = $Author->create(['name' => $authorData['name']]);
            foreach ($authorData['books'] as $book) {
                $bookClass->create([
                    'title' => $book['title'],
                    'description' => $book['description'],
                    'published_year' => $book['published_year'],
                    'author_id' => $author->id,
                ]);
            }
        }
    }
}