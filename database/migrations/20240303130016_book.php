<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;

final class Book extends \App\Core\BaseMigration
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
        Capsule::schema()->create('books', function ($table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('published_year')->nullable();
            $table->timestamps();
            $table->foreignId('author_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        $this->table('books')->drop()->save();
    }
}