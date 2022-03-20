<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TipsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TipsTable Test Case
 */
class TipsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var TipsTable
     */
    public $Tips;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.tips',
        'app.stores',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Tips') ? [] : ['className' => TipsTable::class];
        $this->Tips = TableRegistry::getTableLocator()->get('Tips', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Tips);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
