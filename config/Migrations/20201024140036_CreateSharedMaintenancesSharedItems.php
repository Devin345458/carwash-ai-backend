<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateSharedMaintenancesSharedItems extends AbstractMigration
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
        $table = $this->table('shared_maintenances_shared_items')
            ->addColumn(
                'shared_maintenance_id',
                'integer',
                [
                    'default' => null,
                    'limit' => 11,
                    'null' => false,
                ]
            )
            ->addColumn(
                'shared_item_id',
                'integer',
                [
                    'default' => null,
                    'limit' => 11,
                    'null' => false,
                ]
            )
            ->addColumn(
                'quantity',
                'integer',
                [
                    'default' => null,
                    'limit' => 11,
                    'null' => false,
                ]
            );
        $table->create();
    }
}
