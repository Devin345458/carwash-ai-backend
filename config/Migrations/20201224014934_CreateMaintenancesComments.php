<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateMaintenancesComments extends AbstractMigration
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
        $table->addColumn('maintenance_sessions_maintenance_id', 'integer');
        $table->addColumn('comment_id', 'integer');
        $table->create();
    }
}
