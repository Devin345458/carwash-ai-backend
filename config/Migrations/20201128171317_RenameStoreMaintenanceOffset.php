<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class RenameStoreMaintenanceOffset extends AbstractMigration
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
        $table = $this->table('store_settings');
        $table->renameColumn('due_days', 'maintenance_due_days_offset');
        $table->renameColumn('due_cars', 'maintenance_due_cars_offset');
        $table->renameColumn('upcoming_days', 'upcoming_days_offset');
        $table->renameColumn('upcoming_cars', 'upcoming_cars_offset');
        $table->update();
    }
}
