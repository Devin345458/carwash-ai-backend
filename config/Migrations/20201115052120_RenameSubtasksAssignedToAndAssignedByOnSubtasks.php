<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class RenameSubtasksAssignedToAndAssignedByOnSubtasks extends AbstractMigration
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
        $table = $this->table('subtasks');
        $table->renameColumn('assigned_to', 'assigned_to_id');
        $table->renameColumn('assigned_by', 'assigned_by_id');
        $table->update();
    }
}
