<?php

declare(strict_types=1);

final class Role extends \Phinx\Migration\AbstractMigration
{
    use \App\Traits\ConnectionAble;

    /**
     * @throws Exception
     */
    public function up(): void
    {
        $db = $this->getDatabaseConnection();

        $sql = "CREATE TABLE roles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

        $db->query($sql);
    }

    /**
     * @throws Exception
     */
    public function down(): void
    {
        $db = $this->getDatabaseConnection();

        $sql = "DROP TABLE IF EXISTS roles";

        $db->query($sql);
    }
}
