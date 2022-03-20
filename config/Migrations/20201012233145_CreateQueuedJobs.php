<?php

use Migrations\AbstractMigration;

class CreateQueuedJobs extends AbstractMigration
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
        $this->table('queued_jobs')

            ->addColumn(
                'job_type',
                'string',
                [
                'default' => null,
                'limit' => 45,
                'null' => false,
                ]
            )
            ->addColumn(
                'data',
                'text',
                [
                'default' => null,
                'limit' => 16777215,
                'null' => true,
                ]
            )
            ->addColumn(
                'job_group',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => true,
                ]
            )
            ->addColumn(
                'reference',
                'string',
                [
                'default' => null,
                'limit' => 255,
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
                'notbefore',
                'datetime',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'fetched',
                'datetime',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'completed',
                'datetime',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'progress',
                'float',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'failed',
                'integer',
                [
                'default' => '0',
                'limit' => 11,
                'null' => false,
                ]
            )
            ->addColumn(
                'failure_message',
                'text',
                [
                'default' => null,
                'limit' => 16777215,
                'null' => true,
                ]
            )
            ->addColumn(
                'workerkey',
                'string',
                [
                'default' => null,
                'limit' => 45,
                'null' => true,
                ]
            )
            ->addColumn(
                'status',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => true,
                ]
            )
            ->addColumn(
                'priority',
                'integer',
                [
                'default' => '5',
                'limit' => 3,
                'null' => false,
                ]
            )
            ->create();
    }
}
