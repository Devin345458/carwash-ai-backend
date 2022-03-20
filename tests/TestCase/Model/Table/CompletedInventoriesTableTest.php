<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CompletedInventoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CompletedinventorysTable Test Case
 */
class CompletedInventoriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var CompletedInventoriesTable
     */
    public $Completedinventorys;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Completedinventorys',
        'app.Users',
        'app.Stores',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Completedinventorys') ? [] : ['className' => CompletedInventoriesTable::class];
        $this->Completedinventorys = TableRegistry::getTableLocator()->get('Completedinventorys', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Completedinventorys);

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
