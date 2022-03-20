<?php

use Migrations\AbstractMigration;

class CreateSupplierTypes extends AbstractMigration
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
        $table = $this->table('supplier_types');
        $table->addColumn('name', 'string');
        $table->create();
    }
}
