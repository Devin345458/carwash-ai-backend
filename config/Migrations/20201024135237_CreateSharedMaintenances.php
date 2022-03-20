<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateSharedMaintenances extends AbstractMigration
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
        $table = $this->table('shared_maintenances')
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
                'method',
                'string',
                [
                    'default' => null,
                    'limit' => 255,
                    'null' => false,
                ]
            )
            ->addColumn(
                'expected_duration',
                'integer',
                [
                    'default' => null,
                    'limit' => 5,
                    'null' => true,
                ]
            )
            ->addColumn(
                'frequency_days',
                'integer',
                [
                    'default' => null,
                    'limit' => 11,
                    'null' => true,
                ]
            )
            ->addColumn(
                'frequency_cars',
                'integer',
                [
                    'default' => null,
                    'limit' => 11,
                    'null' => true,
                ]
            )
            ->addColumn(
                'photo_id',
                'integer',
                [
                    'default' => null,
                    'limit' => 11,
                    'null' => true,
                ]
            )
            ->addColumn(
                'draft',
                'tinyinteger',
                [
                    'default' => null,
                    'limit' => 4,
                    'null' => false,
                ]
            )
            ->addColumn(
                'shared_equipment_id',
                'integer',
                [
                    'default' => null,
                    'limit' => 11,
                    'null' => true,
                ]
            )
            ->addColumn(
                'store_id',
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
                    'null' => true,
                ]
            )
            ->addColumn(
                'modified',
                'datetime',
                [
                    'default' => null,
                    'limit' => null,
                    'null' => true,
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
                'due_date',
                'date',
                [
                    'default' => null,
                    'limit' => null,
                    'null' => true,
                ]
            )
            ->addColumn(
                'due_cars',
                'integer',
                [
                    'default' => null,
                    'limit' => 11,
                    'null' => true,
                ]
            )
            ->addColumn(
                'deleted',
                'datetime',
                [
                    'default' => null,
                    'limit' => null,
                    'null' => true,
                ]
            )
            ->addIndex(
                [
                    'shared_equipment_id',
                ]
            );
        $table->create();
    }
}
