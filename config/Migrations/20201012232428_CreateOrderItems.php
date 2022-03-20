<?php

use Migrations\AbstractMigration;

class CreateOrderItems extends AbstractMigration
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
        $this->table('order_items')

            ->addColumn(
                'quantity',
                'integer',
                [
                'default' => null,
                'limit' => 11,
                'null' => false,
                ]
            )
            ->addColumn(
                'order_item_status_id',
                'integer',
                [
                'comment' => '1 = Pending, 2 = Approved, 3 = Denied',
                'default' => null,
                'limit' => 11,
                'null' => false,
                ]
            )
            ->addColumn(
                'order_id',
                'integer',
                [
                'default' => null,
                'limit' => 11,
                'null' => false,
                ]
            )
            ->addColumn(
                'inventory_id',
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
                'received_by',
                'uuid',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'receiving_comment',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => true,
                ]
            )
            ->addColumn(
                'shippingslip',
                'string',
                [
                'default' => null,
                'limit' => 500,
                'null' => true,
                ]
            )
            ->addColumn(
                'location',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => true,
                ]
            )
            ->addColumn(
                'expected_delivery_date',
                'date',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
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
                'trackingnumber',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => true,
                ]
            )
            ->addColumn(
                'purchase_cost',
                'float',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addIndex(
                [
                    'order_id',
                ]
            )
            ->create();
    }
}
