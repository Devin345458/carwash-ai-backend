<?php
declare(strict_types=1);

use Cake\ORM\Locator\LocatorAwareTrait;
use Migrations\AbstractMigration;

class AddDefaultItemTypesOnTransactionActions extends AbstractMigration
{
    use LocatorAwareTrait;

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
        $table = $this->getTableLocator()->get('TransactionActions');
        $entities = $table->newEntities([
           [
               'id' => 1,
               'name' => 'Initial Stock',
               'operation' => 2,
           ],
            [
                'id' => 2,
                'name' => 'Received Stock',
                'operation' => 0,
            ],
            [
                'id' => 3,
                'name' => 'Stock Used',
                'operation' => 1,
            ],
            [
                'id' => 4,
                'name' => 'Used in repair',
                'operation' => 1,
            ],
            [
                'id' => 5,
                'name' => 'Used in maintenance',
                'operation' => 1,
            ],
            [
                'id' => 6,
                'name' => 'Inventory Conducted',
                'operation' => 2,
            ],
        ]);
        $table->saveMany($entities);
    }
}
