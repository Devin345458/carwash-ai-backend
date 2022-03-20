<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RepairsToolsFixture
 */
class RepairsToolsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'repair_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'tool_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'repair_tool_fbk1' => ['type' => 'index', 'columns' => ['tool_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['repair_id', 'tool_id'], 'length' => []],
            'repair_tool_fbk1' => ['type' => 'foreign', 'columns' => ['tool_id'], 'references' => ['tools', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'repair_tool_fbk2' => ['type' => 'foreign', 'columns' => ['repair_id'], 'references' => ['repairs', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'repair_id' => 1,
                'tool_id' => 1,
            ],
        ];
        parent::init();
    }
}
