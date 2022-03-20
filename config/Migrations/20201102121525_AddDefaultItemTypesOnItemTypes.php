<?php
declare(strict_types=1);

use Cake\ORM\Locator\LocatorAwareTrait;
use Migrations\AbstractMigration;

class AddDefaultItemTypesOnItemTypes extends AbstractMigration
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
    public function up()
    {
        $table = $this->getTableLocator()->get('ItemTypes');
        $item_types = $table->newEntities([
            [
                'name' => 'Parts'
            ],
            [
                'name' => 'Consumables'
            ],
            [
                'name' => 'Tools'
            ]
        ]);
        $table->saveMany($item_types);
    }

    public function down()
    {
        $table = $this->getTableLocator()->get('ItemTypes');
        $table->deleteAll(['name IN' => ['Parts', 'Consumables', 'Tools']]);
    }
}
