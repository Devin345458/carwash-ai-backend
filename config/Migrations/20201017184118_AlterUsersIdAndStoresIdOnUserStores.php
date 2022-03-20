<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AlterUsersIdAndStoresIdOnUserStores extends AbstractMigration
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
        $table = $this->table('user_stores');
        $table->renameColumn('users_id', 'user_id');
        $table->renameColumn('stores_id', 'store_id');
        $table->rename('users_stores');
        $table->update();
    }
}
