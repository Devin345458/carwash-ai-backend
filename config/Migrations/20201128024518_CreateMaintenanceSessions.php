<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateMaintenanceSessions extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('maintenance_sessions');
        $table->addColumn('start_time', 'timestamp');
        $table->addColumn('end_time', 'timestamp', [
            'null' => true,
        ]);
        $table->addColumn('created_by_id', 'uuid', [
            'default' => null,
            'limit' => null,
            'null' => false,
        ]);
        $table->addColumn('modified_by_id', 'uuid', [
            'default' => null,
            'limit' => null,
            'null' => false,
        ]);
        $table->create();
    }
}
