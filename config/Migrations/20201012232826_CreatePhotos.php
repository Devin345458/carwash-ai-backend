<?php

use Migrations\AbstractMigration;

class CreatePhotos extends AbstractMigration
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
        $this->table('photos')

            ->addColumn(
                'name',
                'string',
                [
                'default' => null,
                'limit' => 10000,
                'null' => false,
                ]
            )
            ->addColumn(
                'dir',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => false,
                ]
            )
            ->addColumn(
                'size',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => false,
                ]
            )
            ->addColumn(
                'type',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => false,
                ]
            )
            ->create();
    }
}
