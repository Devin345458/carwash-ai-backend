<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class RemoveUnusedTables extends AbstractMigration
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
        $this->table('subtasks')->drop()->update();
        $this->table('shared_equipments')->drop()->update();
        $this->table('shared_items')->drop()->update();
        $this->table('shared_maintenances')->drop()->update();
        $this->table('shared_maintenances_shared_items')->drop()->update();
        $this->table('shared_maintenances_shared_items')->drop()->update();
        $this->table('comments_maintenance_sessions_maintenances')->drop()->update();
        $this->table('completed_maintenances')->drop()->update();
        $this->table('claims')->drop()->update();
        $this->table('inventories_suppliers')->drop()->update();
        $this->table('order_item_status_histories')->drop()->update();
        $this->table('tools')->drop()->update();
        $this->table('warranties')->drop()->update();
    }
}
