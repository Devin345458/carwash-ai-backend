<?php

use Migrations\AbstractMigration;

class CreateInventoriesSuppliers extends AbstractMigration
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
        $table = $this->table('inventories_suppliers');
        $table->addColumn(
            'inventory_id',
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
            'price',
            'decimal',
            [
            'default' => null,
            'null' => false,
            ]
        );
        $table->addColumn(
            'in_stock',
            'boolean',
            [
            'default' => null,
            'null' => false,
            ]
        );
        $table->addColumn(
            'product_page',
            'string',
            [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]
        );
        $table->create();
    }
}
