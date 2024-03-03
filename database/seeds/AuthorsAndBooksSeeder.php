<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Phinx\Seed\AbstractSeed;

class AuthorsAndBooksSeeder extends AbstractSeed {
    public function run(): void
    {
        $connection = config('database.default');

        $connectionConfigs = config("database.connections.$connection");

        $capsule = new Capsule();
        $capsule->addConnection([
            'driver' => $connectionConfigs['driver'],
            'host' => $connectionConfigs['host'],
            'database' => $connectionConfigs['database'],
            'username' => $connectionConfigs['username'],
            'password' => $connectionConfigs['password'],
            'charset' => $connectionConfigs['charset'],
            'collation' => $connectionConfigs['collation'],
            'prefix' => $connectionConfigs['prefix'],
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

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
        for ($i = 1; $i <= 19; $i++) {
            $authorsData[] = [
                'name' => 'Автор ' . $i,
                'books' => []
            ];
            $numberOfBooks = rand(10, 20); // Случайное количество книг от 10 до 20
            for ($j = 1; $j <= $numberOfBooks; $j++) {
                $authorsData[$i]['books'][] = [
                    'title' => 'Книга ' . $j . ' от автора ' . $i,
                    'description' => 'Описание книги ' . $j . ' от автора ' . $i,
                    'published_year' => rand(1800, 2022) // Случайный год издания
                ];
            }
        }

        foreach ($authorsData as $authorData) {
            $author = \App\Models\Author::create(['name' => $authorData['name']]);

            foreach ($authorData['books'] as $book) {
                $author->books()->create($book);
            }
        }
    }
}