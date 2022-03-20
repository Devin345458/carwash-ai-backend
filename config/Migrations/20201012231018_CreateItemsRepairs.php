<?php

use Migrations\AbstractMigration;

class CreateItemsRepairs extends AbstractMigration
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
        $this->table('items_repairs')
            ->addColumn(
                'repair_id',
                'integer',
                [
                'default' => null,
                'limit' => 11,
                'null' => false,
                ]
            )
            ->addColumn(
                'item_id',
                'integer',
                [
                'default' => null,
                'limit' => 11,
                'null' => false,
                ]
            )
            ->create();
    }
}
