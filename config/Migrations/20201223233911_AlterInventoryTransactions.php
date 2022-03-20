<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AlterInventoryTransactions extends AbstractMigration
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
        $table->removeColumn('model');
        $table->removeColumn('foreign_key');
        $table->update();
    }
}
