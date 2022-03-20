<?php

use Migrations\AbstractMigration;

class CreateCompletedRepairs extends AbstractMigration
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
        $table = $this->table('completed_repairs');
        $table->addColumn(
            'reason',
            'integer',
            [
            'default' => null,
            'limit' => 11,
            'null' => false,
            ]
        );
        $table->addColumn(
            'other_reason',
            'string',
            [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]
        );
        $table->addColumn(
            'signature',
            'string',
            [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]
        );
        $table->addColumn(
            'repair_id',
            'integer',
            [
            'default' => null,
            'limit' => 11,
            'null' => false,
            ]
        );
        $table->addColumn(
            'completed_by',
            'uuid',
            [
            'default' => null,
            'null' => false,
            ]
        );
        $table->addColumn(
            'completed_datetime',
            'datetime',
            [
            'default' => null,
            'null' => false,
            ]
        );
        $table->create();
    }
}
