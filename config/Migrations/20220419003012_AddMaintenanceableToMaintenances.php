<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddMaintenanceableToMaintenances extends AbstractMigration
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
        $table->renameColumn('equipment_id', 'maintainable_id');
        $table->addColumn('maintainable_type', 'string', [
            'after' => 'maintainable_id'
        ]);
        $table->update();

        $this->query("UPDATE maintenances SET maintainable_type = 'Equipments'");
    }
}
