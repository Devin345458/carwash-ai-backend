<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * InventorysFixture
 */
class InventorysFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'current_stock' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'initial_stock' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'stores_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'model' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'foreign_key' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'last_inventory_date' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'stores_id' => ['type' => 'index', 'columns' => ['stores_id'], 'length' => []],
            'created_by' => ['type' => 'index', 'columns' => ['created_by'], 'length' => []],
            'foreign_key' => ['type' => 'index', 'columns' => ['foreign_key'], 'length' => []],
            'model' => ['type' => 'index', 'columns' => ['model'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'inventory_createdby' => ['type' => 'foreign', 'columns' => ['created_by'], 'references' => ['Users', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'inventory_stores' => ['type' => 'foreign', 'columns' => ['stores_id'], 'references' => ['stores', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'current_stock' => 1,
                'initial_stock' => 1,
                'stores_id' => 1,
                'model' => 'Lorem ipsum dolor sit amet',
                'foreign_key' => 1,
                'last_inventory_date' => '2018-10-27 19:20:32',
                'created' => '2018-10-27 19:20:32',
                'modified' => '2018-10-27 19:20:32',
                'created_by' => 'f2c56e6d-d9d8-45b2-99d0-e38d03a7022e',
            ],
        ];
        parent::init();
    }
}
