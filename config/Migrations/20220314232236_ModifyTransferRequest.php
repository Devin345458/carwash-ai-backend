<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class ModifyTransferRequest extends AbstractMigration
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
        $table = $this->table('transfer_requests');
        $table->removeColumn('requested_by');
        $table->renameColumn('modified_by', 'modified_by_id');
        $table->addColumn('created_by_id', 'uuid');
        $table->renameColumn('approved_by', 'approved_by_id');
        $table->renameColumn('requested', 'created');
        $table->changeColumn('to_store_id', 'uuid', [
            'null' => false,
        ]);
        $table->changeColumn('from_store_id', 'uuid', [
            'null' => false,
        ]);
        $table->update();
    }
}
