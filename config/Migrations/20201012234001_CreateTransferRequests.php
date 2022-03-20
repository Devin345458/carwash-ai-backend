<?php

use Migrations\AbstractMigration;

class CreateTransferRequests extends AbstractMigration
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
        $this->table('transfer_requests')

            ->addColumn(
                'to_store_id',
                'integer',
                [
                'default' => null,
                'limit' => 11,
                'null' => false,
                ]
            )
            ->addColumn(
                'from_store_id',
                'integer',
                [
                'default' => null,
                'limit' => 11,
                'null' => false,
                ]
            )
            ->addColumn(
                'transfer_status_id',
                'integer',
                [
                'default' => null,
                'limit' => 11,
                'null' => false,
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
                'requested',
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
                'requested_by',
                'uuid',
                [
                'default' => null,
                'limit' => null,
                'null' => false,
                ]
            )
            ->addColumn(
                'order_item_id',
                'integer',
                [
                'default' => null,
                'limit' => 11,
                'null' => false,
                ]
            )
            ->addColumn(
                'approved_by',
                'uuid',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'denial_reason',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => true,
                ]
            )
            ->create();
    }
}
