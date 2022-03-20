<?php

use Migrations\AbstractMigration;

class CreateStoreTypes extends AbstractMigration
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
        $table = $this->table('store_types');
        $table->addColumn('name', 'string');
        $table->create();
    }
}
