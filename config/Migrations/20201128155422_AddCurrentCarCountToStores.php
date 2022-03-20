<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddCurrentCarCountToStores extends AbstractMigration
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
        $table = $this->table('stores');
        $table->addColumn('current_car_count', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
        ]);
        $table->update();
    }
}
