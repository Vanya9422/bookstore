<?php

declare(strict_types=1);

final class Author extends \Phinx\Migration\AbstractMigration
{
    use \App\Traits\ConnectionAble;

    /**
     * @throws Exception
     */
    public function up(): void
    {
        // Получаем объект соединения с базой данных
        $db = $this->getDatabaseConnection();

        // Выполняем SQL-запрос для создания таблицы `authors`
        $sql = "CREATE TABLE authors (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

        // Используем объект соединения для выполнения запроса
        $db->query($sql);
    }

    /**
     * @throws Exception
     */
    public function down(): void
    {
        // Получаем объект соединения с базой данных
        $db = $this->getDatabaseConnection();

        // Выполняем SQL-запрос для удаления таблицы `authors`
        $sql = "DROP TABLE IF EXISTS authors";

        // Используем объект соединения для выполнения запроса
        $db->query($sql);
    }
}
