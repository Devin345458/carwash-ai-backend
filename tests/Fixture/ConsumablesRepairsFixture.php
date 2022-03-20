<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ConsumablesRepairsFixture
 */
class ConsumablesRepairsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'consumable_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'repair_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'consumables_repairs_fbk2' => ['type' => 'index', 'columns' => ['repair_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['consumable_id', 'repair_id'], 'length' => []],
            'consumables_repairs_fbk1' => ['type' => 'foreign', 'columns' => ['consumable_id'], 'references' => ['consumables', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'consumables_repairs_fbk2' => ['type' => 'foreign', 'columns' => ['repair_id'], 'references' => ['repairs', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'consumable_id' => 1,
                'repair_id' => 1,
            ],
        ];
        parent::init();
    }
}
