<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RepairsToolsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RepairsToolsTable Test Case
 */
class RepairsToolsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var RepairsToolsTable
     */
    public $RepairsTools;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.repairs_tools',
        'app.repairs',
        'app.tools',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('RepairsTools') ? [] : ['className' => RepairsToolsTable::class];
        $this->RepairsTools = TableRegistry::getTableLocator()->get('RepairsTools', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RepairsTools);

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
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
