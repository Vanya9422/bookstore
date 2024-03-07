<?php

use Phinx\Seed\AbstractSeed;

class RoleSeeder extends AbstractSeed
{

    use \App\Traits\ConnectionAble;

    /**
     * @throws Exception
     */
    public function run(): void {
        $role = new \App\Models\Role();
        $role->setConnection(
            $this->getDatabaseConnection()
        );
        $role->create(['name' => 'admin']);
        $role->create(['name' => 'user']);
    }
}