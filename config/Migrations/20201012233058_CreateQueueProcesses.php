<?php

use Migrations\AbstractMigration;

class CreateQueueProcesses extends AbstractMigration
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
        $this->table('queue_processes')

            ->addColumn(
                'pid',
                'string',
                [
                'default' => null,
                'limit' => 40,
                'null' => false,
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
                'terminate',
                'boolean',
                [
                'default' => false,
                'limit' => null,
                'null' => false,
                ]
            )
            ->addColumn(
                'server',
                'string',
                [
                'default' => null,
                'limit' => 90,
                'null' => true,
                ]
            )
            ->addColumn(
                'workerkey',
                'string',
                [
                'default' => null,
                'limit' => 45,
                'null' => false,
                ]
            )
            ->addIndex(
                [
                    'workerkey',
                ],
                ['unique' => true]
            )
            ->addIndex(
                [
                    'pid',
                    'server',
                ],
                ['unique' => true]
            )
            ->create();
    }
}
