<?php

declare(strict_types=1);

final class User extends \Phinx\Migration\AbstractMigration
{
    use \App\Traits\ConnectionAble;


    /**
     * @throws Exception
     */
    public function up(): void
    {
        $db = $this->getDatabaseConnection();

        $sql = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (role_id) REFERENCES roles(id)
        )";

        $db->query($sql);
    }

    /**
     * @throws Exception
     */
    public function down(): void
    {
        $db = $this->getDatabaseConnection();

        $sql = "DROP TABLE IF EXISTS users";

        $db->query($sql);
    }
}
