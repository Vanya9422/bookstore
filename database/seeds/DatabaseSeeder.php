<?php

use Phinx\Seed\AbstractSeed;

class DatabaseSeeder extends AbstractSeed
{

    use \App\Traits\ConnectionAble;

    /**
     * @throws Exception
     */
    public function run(): void
    {
        $roleSeeder = new RoleSeeder();
        $roleSeeder->setAdapter($this->getAdapter());
        $roleSeeder->run();

        $userSeeder = new AdminAndUserSeeder();
        $userSeeder->setAdapter($this->getAdapter()); // передаем адаптер БД
        $userSeeder->run();

        $userSeeder = new AuthorsAndBooksSeeder();
        $userSeeder->setAdapter($this->getAdapter()); // передаем адаптер БД
        $userSeeder->run();
    }
}