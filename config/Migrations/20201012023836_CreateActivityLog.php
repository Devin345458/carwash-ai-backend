<?php

use Migrations\AbstractMigration;

class CreateActivityLog extends AbstractMigration
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
        $table = $this->table('activity_logs');
        $table->addColumn(
            'created_at',
            'timestamp',
            [
            'null' => true,
            ]
        );
        $table->addColumn(
            'scope_model',
            'string',
            [
            'length' => 64,
            'null' => false,
            ]
        );
        $table->addColumn(
            'scope_id',
            'string',
            [
            'length' => 64,
            'null' => false,
            ]
        );
        $table->addColumn(
            'issuer_model',
            'string',
            [
            'length' => 64,
            'null' => false,
            ]
        );
        $table->addColumn(
            'issuer_id',
            'string',
            [
            'length' => 64,
            'null' => false,
            ]
        );
        $table->addColumn(
            'object_model',
            'string',
            [
            'length' => 64,
            'null' => false,
            ]
        );
        $table->addColumn(
            'object_id',
            'string',
            [
            'length' => 64,
            'null' => false,
            ]
        );
        $table->addColumn(
            'level',
            'string',
            [
            'length' => 64,
            'null' => false,
            ]
        );
        $table->addColumn(
            'action',
            'string',
            [
            'length' => 64,
            'null' => false,
            ]
        );
        $table->addColumn(
            'message',
            'text',
            [
            'null' => false,
            ]
        );
        $table->addColumn(
            'data',
            'text',
            [
            'null' => false,
            ]
        );
        $table->create();
    }
}
