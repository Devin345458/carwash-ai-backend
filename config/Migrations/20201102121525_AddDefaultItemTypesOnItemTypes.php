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
        $this->query("INSERT INTO item_types (name) values ('Parts')");
        $this->query("INSERT INTO item_types (name) values ('Consumables')");
        $this->query("INSERT INTO item_types (name) values ('Tools')");
    }

    public function down()
    {
        $table = $this->getTableLocator()->get('ItemTypes');
        $table->deleteAll(['name IN' => ['Parts', 'Consumables', 'Tools']]);
    }
}
