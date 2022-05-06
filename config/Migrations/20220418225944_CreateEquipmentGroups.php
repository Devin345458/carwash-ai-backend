<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateEquipmentGroups extends AbstractMigration
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
        $table = $this->table('equipment_groups');
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('store_id', 'uuid');
        $table->addTimestamps();
        $table->create();

        $table = $this->table('equipment_groups_equipments');
        $table->addColumn('equipment_id', 'integer');
        $table->addColumn('equipment_group_id', 'integer');
        $table->addTimestamps();
        $table->create();

    }
}
