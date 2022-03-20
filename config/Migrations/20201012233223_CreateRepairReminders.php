<?php

use Migrations\AbstractMigration;

class CreateRepairReminders extends AbstractMigration
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
        $this->table('repair_reminders')

            ->addColumn(
                'reminder',
                'datetime',
                [
                'default' => null,
                'limit' => null,
                'null' => false,
                ]
            )
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
                'myuser_id',
                'uuid',
                [
                'default' => null,
                'limit' => null,
                'null' => false,
                ]
            )
            ->addColumn(
                'sent',
                'boolean',
                [
                'default' => false,
                'limit' => null,
                'null' => true,
                ]
            )
            ->create();
    }
}
