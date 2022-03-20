<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class UpdateMaintenances extends AbstractMigration
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
        $table = $this->table('maintenances');
        $table->renameColumn('due_date', 'last_completed_date');
        $table->renameColumn('due_cars', 'last_cars_completed');
        $table->renameColumn('created_by', 'created_by_id');
        $table->renameColumn('modified_by', 'modified_by_id');
        $table->renameColumn('procedure', 'procedures');
        $table->update();
    }
}
