<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class DropStatusesTables extends AbstractMigration
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
        $table = $this->table('order_item_statuses');
        $table->drop();
        $table->update();
        $table = $this->table('transfer_statuses');
        $table->drop();
        $table->update();
    }
}
