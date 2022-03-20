<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddUserFieldsToItemTypes extends AbstractMigration
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
        $table = $this->table('item_types');
        $table->renameColumn('modified_by', 'modified_by_id');
        $table->renameColumn('created_by', 'created_by_id');
        $table->update();
    }
}
