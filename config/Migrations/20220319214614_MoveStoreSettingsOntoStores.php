<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class MoveStoreSettingsOntoStores extends AbstractMigration
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
        $table = $this->table('stores');
        $table->addColumn('allow_car_counts', 'boolean');
        $table->addColumn('maintenance_due_days_offset', 'integer', [
            'default' => 0
        ]);
        $table->addColumn('maintenance_due_cars_offset', 'integer', [
            'default' => 0
        ]);
        $table->addColumn('upcoming_days_offset', 'integer', [
            'default' => 2
        ]);
        $table->addColumn('upcoming_cars_offset', 'integer', [
            'default' => 2000
        ]);
        $table->addColumn('time_zone', 'string', [
            'default' => 'American/Chicago'
        ]);
        $table->addColumn('require_scan', 'boolean', [
            'default' => 0
        ]);
        $table->update();
        $this->table('store_settings')->drop()->update();
    }
}
