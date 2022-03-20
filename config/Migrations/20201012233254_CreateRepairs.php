<?php

use Migrations\AbstractMigration;

class CreateRepairs extends AbstractMigration
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
        $this->table('repairs')

            ->addColumn(
                'name',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => false,
                ]
            )
            ->addColumn(
                'description',
                'text',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'time',
                'integer',
                [
                'default' => '0',
                'limit' => 11,
                'null' => true,
                ]
            )
            ->addColumn(
                'due_date',
                'date',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'reminder',
                'datetime',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'priority',
                'integer',
                [
                'default' => '0',
                'limit' => 11,
                'null' => true,
                ]
            )
            ->addColumn(
                'health_impact',
                'float',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'status',
                'string',
                [
                'default' => 'Pending Assignment',
                'limit' => 255,
                'null' => false,
                ]
            )
            ->addColumn(
                'solution',
                'text',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'store_id',
                'integer',
                [
                'default' => null,
                'limit' => 11,
                'null' => false,
                ]
            )
            ->addColumn(
                'equipment_id',
                'integer',
                [
                'default' => null,
                'limit' => 11,
                'null' => true,
                ]
            )
            ->addColumn(
                'assigned',
                'uuid',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'assigned_by',
                'uuid',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'assigned_date',
                'datetime',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'modified',
                'datetime',
                [
                'default' => null,
                'limit' => null,
                'null' => false,
                ]
            )
            ->addColumn(
                'maintenance_id',
                'integer',
                [
                'default' => null,
                'limit' => 11,
                'null' => true,
                ]
            )
            ->addColumn(
                'repair_id',
                'integer',
                [
                'default' => null,
                'limit' => 11,
                'null' => true,
                ]
            )
            ->addColumn(
                'created',
                'datetime',
                [
                'default' => null,
                'limit' => null,
                'null' => false,
                ]
            )
            ->addColumn(
                'created_by',
                'uuid',
                [
                'default' => null,
                'limit' => null,
                'null' => false,
                ]
            )
            ->addColumn(
                'modified_by',
                'uuid',
                [
                'default' => null,
                'limit' => null,
                'null' => false,
                ]
            )
            ->addColumn(
                'completed',
                'boolean',
                [
                'default' => false,
                'limit' => null,
                'null' => false,
                ]
            )
            ->addIndex(
                [
                    'created_by',
                ]
            )
            ->addIndex(
                [
                    'assigned',
                ]
            )
            ->addIndex(
                [
                    'assigned_by',
                ]
            )
            ->addIndex(
                [
                    'maintenance_id',
                ]
            )
            ->create();
    }
}
