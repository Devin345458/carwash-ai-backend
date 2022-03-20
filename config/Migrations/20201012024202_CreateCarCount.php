<?php

use Migrations\AbstractMigration;

class CreateCarCount extends AbstractMigration
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
        $table = $this->table('car_counts');
        $table->addColumn('carcount', 'integer');
        $table->addColumn('store_id', 'integer');
        $table->addColumn('date_of_cars', 'date');
        $table->addColumn('created', 'datetime');
        $table->addColumn('modified', 'datetime');
        $table->addColumn('created_by', 'uuid');
        $table->addColumn('modified_by', 'uuid');
        $table->create();
    }
}
