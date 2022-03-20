<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CompletedMaintenancesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CompletedMaintenancesTable Test Case
 */
class CompletedMaintenancesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var CompletedMaintenancesTable
     */
    public $CompletedMaintenances;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.completedmaintenances',
        'app.maintenances',
        'app.Users',
        'app.comments',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CompletedMaintenances') ? [] : ['className' => CompletedMaintenancesTable::class];
        $this->CompletedMaintenances = TableRegistry::getTableLocator()->get('CompletedMaintenances', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CompletedMaintenances);

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

    /**
     * Test completeMaintenance method
     *
     * @return void
     */
    public function testCompleteMaintenance()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
