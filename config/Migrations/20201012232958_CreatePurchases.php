<?php

use Migrations\AbstractMigration;

class CreatePurchases extends AbstractMigration
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
        $this->table('purchases')

            ->addColumn(
                'expected_delivery_date',
                'date',
                [
                'default' => null,
                'limit' => null,
                'null' => false,
                ]
            )
            ->addColumn(
                'actual_delivery_date',
                'date',
                [
                'default' => null,
                'limit' => null,
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
                'modified',
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
                'supplier_id',
                'integer',
                [
                'default' => null,
                'limit' => 11,
                'null' => true,
                ]
            )
            ->addColumn(
                'trackingnumber',
                'string',
                [
                'default' => null,
                'limit' => 56,
                'null' => true,
                ]
            )
            ->addIndex(
                [
                    'supplier_id',
                ]
            )
            ->create();
    }
}
