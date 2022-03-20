<?php

use Migrations\AbstractMigration;

class CreatePhotosRepairs extends AbstractMigration
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
        $table = $this->table('photos_repairs');
        $table->addColumn('photo_id', 'string');
        $table->addColumn('repair_id', 'string');
        $table->addColumn('cover', 'boolean');
        $table->create();
    }
}
