<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class ChangeStoreIdToUUID extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * @return void
     */
    public function change()
    {
        $table = $this->table('stores_users');
        $table->changeColumn('store_id', 'uuid');
        $table->update();

        $table = $this->table('suppliers');
        $table->changeColumn('store_id', 'uuid');
        $table->update();

        $table = $this->table('store_settings');
        $table->changeColumn('store_id', 'uuid');
        $table->update();

        $table = $this->table('shared_equipments');
        $table->changeColumn('store_id', 'uuid');
        $table->update();

        $table = $this->table('shared_maintenances');
        $table->changeColumn('store_id', 'uuid');
        $table->update();

        $table = $this->table('maintenances');
        $table->changeColumn('store_id', 'uuid');
        $table->update();

        $table = $this->table('locations');
        $table->changeColumn('store_id', 'uuid');
        $table->update();

        $table = $this->table('inventories');
        $table->changeColumn('store_id', 'uuid');
        $table->update();

        $table = $this->table('completed_inventories');
        $table->changeColumn('store_id', 'uuid');
        $table->update();

        $table = $this->table('car_counts');
        $table->changeColumn('store_id', 'uuid');
        $table->update();

        $table = $this->table('tools');
        $table->changeColumn('store_id', 'uuid');
        $table->update();

        $table = $this->table('equipments');
        $table->changeColumn('store_id', 'uuid');
        $table->update();

        $table = $this->table('users');
        $table->changeColumn('active_store', 'uuid');
        $table->update();

        $table = $this->table('repairs');
        $table->changeColumn('store_id', 'uuid');
        $table->update();
    }
}
