<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * InventoryUsedFixture
 */
class InventoryUsedFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'inventory_used';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'stock_used' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'store_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'inventorys_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'myuser_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'model' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'external_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'used_by' => ['type' => 'index', 'columns' => ['myuser_id'], 'length' => []],
            'inventory_id' => ['type' => 'index', 'columns' => ['inventorys_id'], 'length' => []],
            'store_id' => ['type' => 'index', 'columns' => ['store_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'inventory_inventory' => ['type' => 'foreign', 'columns' => ['inventorys_id'], 'references' => ['inventorys', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'inventory_used_by' => ['type' => 'foreign', 'columns' => ['myuser_id'], 'references' => ['Users', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'inventory_used_stores' => ['type' => 'foreign', 'columns' => ['store_id'], 'references' => ['stores', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
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
                'stock_used' => 1,
                'store_id' => 1,
                'inventorys_id' => 1,
                'created' => '2018-11-10 00:17:53',
                'myuser_id' => 'f9fbd5ca-aa71-4f65-af6c-9c4cea56c128',
                'model' => 'Lorem ipsum dolor sit amet',
                'external_id' => 1,
            ],
        ];
        parent::init();
    }
}
