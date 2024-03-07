<?php

use Phinx\Seed\AbstractSeed;

class AdminAndUserSeeder extends AbstractSeed
{

    use \App\Traits\ConnectionAble;

    /**
     * @throws Exception
     */
    public function run(): void {
        // Получаем объект соединения с базой данных
        $db = $this->getDatabaseConnection();
        $adminRoleId = $this->fetchRow('SELECT id FROM roles WHERE name = "admin"')['id'];
        $userRoleId = $this->fetchRow('SELECT id FROM roles WHERE name = "user"')['id'];
        $user = new \App\Models\User();
        $user->setConnection($db);

        $user->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role_id' => $adminRoleId
        ]);

        $user->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role_id' => $userRoleId
        ]);
    }
}