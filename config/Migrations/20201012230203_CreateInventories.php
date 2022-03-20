<?php

use Migrations\AbstractMigration;

class CreateInventories extends AbstractMigration
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
        $table = $this->table('inventories');
        $table->addColumn(
            'item_id',
            'integer',
            [
            'default' => null,
            'limit' => 11,
            'null' => false,
            ]
        );
        $table->addColumn(
            'store_id',
            'integer',
            [
            'default' => null,
            'limit' => 11,
            'null' => false,
            ]
        );
        $table->addColumn(
            'supplier_id',
            'integer',
            [
            'default' => null,
            'limit' => 11,
            'null' => false,
            ]
        );
        $table->addColumn(
            'cost',
            'decimal',
            [
            'default' => null,
            'null' => false,
            ]
        );
        $table->addColumn(
            'current_stock',
            'integer',
            [
            'default' => null,
            'limit' => 11,
            'null' => false,
            ]
        );
        $table->addColumn(
            'initial_stock',
            'integer',
            [
            'default' => null,
            'limit' => 11,
            'null' => false,
            ]
        );
        $table->addColumn(
            'desired_stock',
            'integer',
            [
            'default' => null,
            'limit' => 11,
            'null' => false,
            ]
        );
        $table->addColumn(
            'created',
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
