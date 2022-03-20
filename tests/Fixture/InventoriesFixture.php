<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * InventoriesFixture
 */
class InventoriesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'item_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'store_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'supplier_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'cost' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => ''],
        'current_stock' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'initial_stock' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'desired_stock' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'inventories_items_id_fk' => ['type' => 'index', 'columns' => ['item_id'], 'length' => []],
            'inventories_stores_id_fk' => ['type' => 'index', 'columns' => ['store_id'], 'length' => []],
            'inventories_suppliers_id_fk' => ['type' => 'index', 'columns' => ['supplier_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'inventories_items_id_fk' => ['type' => 'foreign', 'columns' => ['item_id'], 'references' => ['items', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'inventories_stores_id_fk' => ['type' => 'foreign', 'columns' => ['store_id'], 'references' => ['stores', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'inventories_suppliers_id_fk' => ['type' => 'foreign', 'columns' => ['supplier_id'], 'references' => ['suppliers', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'item_id' => 1,
                'store_id' => 1,
                'supplier_id' => 1,
                'cost' => 1,
                'current_stock' => 1,
                'initial_stock' => 1,
                'desired_stock' => 1,
            ],
        ];
        parent::init();
    }
}
