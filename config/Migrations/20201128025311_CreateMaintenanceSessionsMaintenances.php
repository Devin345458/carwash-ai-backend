<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateMaintenanceSessionsMaintenances extends AbstractMigration
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
        $table = $this->table('maintenance_sessions_maintenances');
        $table->addColumn('maintenance_id', 'integer');
        $table->addColumn('maintenance_session_id', 'integer');
        $table->addColumn('status', 'boolean', [
            'comment' => '1 = completed, 0 = skipped',
            'default' => 0,
        ]);
        $table->create();
    }
}
