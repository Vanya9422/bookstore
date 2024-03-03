<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;

final class Author extends \App\Core\BaseMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up(): void
    {
        Capsule::schema()->create('authors', function ($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $this->table('authors')->drop()->save();
    }
}
