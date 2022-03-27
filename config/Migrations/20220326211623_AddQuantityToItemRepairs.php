<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddQuantityToItemRepairs extends AbstractMigration
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
        $table = $this->table('items_repairs');
        $table->addColumn('quantity', 'integer', [
            'default' => 1,
            'limit' => 11,
            'null' => false,
        ]);
        $table->update();
    }
}
