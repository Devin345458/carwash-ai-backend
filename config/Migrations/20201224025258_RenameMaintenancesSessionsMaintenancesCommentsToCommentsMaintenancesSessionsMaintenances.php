<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class RenameMaintenancesSessionsMaintenancesCommentsToCommentsMaintenancesSessionsMaintenances extends AbstractMigration
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
        $table = $this->table('maintenances_sessions_maintenances_comments');
        $table->rename('comments_maintenance_sessions_maintenances');
        $table->update();
    }
}
