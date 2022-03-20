<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AlterDescriptionOnCategories extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('categories');
        $table->changeColumn('description', 'string', [
            'null' => true
        ]);
        $table->update();
    }
}
