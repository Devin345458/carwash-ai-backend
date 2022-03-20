<?php

use Migrations\AbstractMigration;

class CreateTransactionActions extends AbstractMigration
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
        $this->table('transaction_actions')

            ->addColumn(
                'name',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => false,
                ]
            )
            ->addColumn(
                'operation',
                'integer',
                [
                'comment' => '0 = Add, 1 = remove, 2 = set',
                'default' => null,
                'limit' => 11,
                'null' => false,
                ]
            )
            ->create();
    }
}
