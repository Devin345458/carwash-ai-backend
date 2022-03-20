<?php

use Migrations\AbstractMigration;

class CreateUsersDevices extends AbstractMigration
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
        $table = $this->table('users_devices');
        $table->addColumn('device_id', 'string');
        $table->addColumn('user_id', 'uuid');
        $table->create();
    }
}
