<?php

use Migrations\AbstractMigration;

class CreateStores extends AbstractMigration
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
        $this->table('stores')

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
                'number',
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
                'modified',
                'datetime',
                [
                'default' => null,
                'limit' => null,
                'null' => false,
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
                'company_id',
                'integer',
                [
                'default' => null,
                'limit' => 11,
                'null' => false,
                ]
            )
            ->addColumn(
                'address',
                'string',
                [
                'default' => null,
                'limit' => 500,
                'null' => true,
                ]
            )
            ->addColumn(
                'state',
                'string',
                [
                'default' => null,
                'limit' => 2,
                'null' => true,
                ]
            )
            ->addColumn(
                'country',
                'string',
                [
                'default' => null,
                'limit' => 20,
                'null' => true,
                ]
            )
            ->addColumn(
                'zipcode',
                'integer',
                [
                'default' => null,
                'limit' => 5,
                'null' => true,
                ]
            )
            ->addColumn(
                'city',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => true,
                ]
            )
            ->addColumn(
                'subscription_id',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => true,
                ]
            )
            ->addColumn(
                'cancel_date',
                'date',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'canceled',
                'boolean',
                [
                'default' => false,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'cancel_reason',
                'string',
                [
                'default' => null,
                'limit' => 10000,
                'null' => true,
                ]
            )
            ->addColumn(
                'setup_id',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => false,
                ]
            )
            ->addColumn(
                'plan_id',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => false,
                ]
            )
            ->addColumn(
                'store_type_id',
                'integer',
                [
                'default' => null,
                'limit' => 11,
                'null' => false,
                ]
            )
            ->create();
    }
}
