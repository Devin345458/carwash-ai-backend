<?php

use Migrations\AbstractMigration;

class CreateCompletedMaintenances extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * @return void
     */
    public function change()
    {
        $table = $this->table('completed_maintenances');
        $table->addColumn(
            'maintenance_id',
            'integer',
            [
            'default' => null,
            'limit' => 11,
            'null' => false,
            ]
        );
        $table->addColumn(
            'time_to_complete',
            'integer',
            [
            'default' => null,
            'limit' => 11,
            'null' => false,
            ]
        );
        $table->addColumn(
            'completed_car_count',
            'integer',
            [
            'default' => null,
            'limit' => 11,
            'null' => false,
            ]
        );
        $table->addColumn(
            'completed_date',
            'datetime',
            [
            'default' => null,
            'null' => false,
            ]
        );
        $table->addColumn(
            'modified',
            'datetime',
            [
            'default' => null,
            'null' => false,
            ]
        );
        $table->addColumn(
            'created_by',
            'uuid',
            [
            'default' => null,
            'null' => false,
            ]
        );
        $table->addColumn(
            'modified_by',
            'uuid',
            [
            'default' => null,
            'null' => false,
            ]
        );
        $table->create();
    }
}
