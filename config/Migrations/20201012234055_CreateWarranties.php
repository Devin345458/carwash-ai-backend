<?php

use Migrations\AbstractMigration;

class CreateWarranties extends AbstractMigration
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
        $this->table('warranties')

            ->addColumn(
                'description',
                'text',
                [
                'default' => null,
                'limit' => null,
                'null' => false,
                ]
            )
            ->addColumn(
                'start',
                'datetime',
                [
                'default' => null,
                'limit' => null,
                'null' => false,
                ]
            )
            ->addColumn(
                'end',
                'datetime',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'created',
                'datetime',
                [
                'default' => null,
                'limit' => null,
                'null' => false,
                ]
            )
            ->addColumn(
                'modified',
                'datetime',
                [
                'default' => null,
                'limit' => null,
                'null' => false,
                ]
            )
            ->addColumn(
                'created_by',
                'uuid',
                [
                'default' => null,
                'limit' => null,
                'null' => false,
                ]
            )
            ->addColumn(
                'modified_by',
                'uuid',
                [
                'default' => null,
                'limit' => null,
                'null' => false,
                ]
            )
            ->addColumn(
                'name',
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
