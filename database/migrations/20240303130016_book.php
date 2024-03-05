<?php

declare(strict_types=1);

final class Book extends \Phinx\Migration\AbstractMigration
{
    use \App\Traits\ConnectionAble;

    /**
     * Создаёт таблицу `books` в базе данных.
     * @throws Exception
     */
    public function up(): void
    {
        // Получаем объект соединения с базой данных
        $db = $this->getDatabaseConnection();

        // SQL-запрос для создания таблицы `books`
        $sql = "CREATE TABLE books (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            published_year INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            author_id INT,
            FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        // Используем объект соединения для выполнения запроса
        $db->query($sql);
    }

    /**
     * Удаляет таблицу `books` из базы данных.
     * @throws Exception
     */
    public function down(): void
    {
        // Получаем объект соединения с базой данных
        $db = $this->getDatabaseConnection();

        // SQL-запрос для удаления таблицы `books`
        $sql = "DROP TABLE IF EXISTS books";

        // Используем объект соединения для выполнения запроса
        $db->query($sql);
    }
}
