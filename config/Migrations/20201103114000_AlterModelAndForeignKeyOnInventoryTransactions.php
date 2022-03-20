<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AlterModelAndForeignKeyOnInventoryTransactions extends AbstractMigration
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
        $table = $this->table('inventory_transactions');
        $table->changeColumn('model', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->changeColumn('foreign_key', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->update();
    }
}
