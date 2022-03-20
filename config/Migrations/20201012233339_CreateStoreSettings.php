<?php

use Migrations\AbstractMigration;

class CreateStoreSettings extends AbstractMigration
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
        $this->table('store_settings')

            ->addColumn(
                'allow_car_counts',
                'boolean',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'due_days',
                'integer',
                [
                'default' => null,
                'limit' => 11,
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
                'upcoming_days',
                'integer',
                [
                'default' => null,
                'limit' => 11,
                'null' => true,
                ]
            )
            ->addColumn(
                'upcoming_cars',
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
                'null' => false,
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
                'null' => true,
                ]
            )
            ->addColumn(
                'modified_by',
                'uuid',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'time_zone',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => true,
                ]
            )
            ->addIndex(
                [
                    'store_id',
                ]
            )
            ->create();
    }
}
