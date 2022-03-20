<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CompletedMaintenancesFixture
 */
class CompletedMaintenancesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'maintenance_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'user_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'completed_date' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'completed_car_count' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'maintenance_id' => ['type' => 'index', 'columns' => ['maintenance_id'], 'length' => []],
            'user_id' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'ompleted_maintenance_fbk1' => ['type' => 'foreign', 'columns' => ['maintenance_id'], 'references' => ['maintenances', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'ompleted_maintenance_fbk2' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['Users', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'maintenance_id' => 1,
                'user_id' => '0c77c9ea-28a7-4780-a1e5-4b71742d6e92',
                'created' => '2018-11-09 02:31:38',
                'completed_date' => 1541730698,
                'completed_car_count' => 1,
            ],
        ];
        parent::init();
    }
}
