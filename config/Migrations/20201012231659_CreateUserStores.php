<?php

use Migrations\AbstractMigration;

class CreateUserStores extends AbstractMigration
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
        $table = $this->table('user_stores');
        $table->addColumn('stores_id', 'integer');
        $table->addColumn('users_id', 'uuid');
        $table->create();
    }
}
