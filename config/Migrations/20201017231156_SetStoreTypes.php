<?php
declare(strict_types=1);

use Cake\ORM\Locator\LocatorAwareTrait;
use Migrations\AbstractMigration;

class SetStoreTypes extends AbstractMigration
{
    use LocatorAwareTrait;

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * @return void
     * @throws Exception
     */
    public function up()
    {
        $storeTypesTable = $this->getTableLocator()->get('store_types');
        $types = $storeTypesTable->newEntities([
            [
                'id' => 1,
                'name' => 'Store',
            ],
            [
                'id' => 2,
                'name' => 'Warehouse',
            ],
        ]);

        $storeTypesTable->saveMany($types);
    }

    public function down()
    {
        $table = $this->table('store_types');
        $table->truncate();
    }
}
