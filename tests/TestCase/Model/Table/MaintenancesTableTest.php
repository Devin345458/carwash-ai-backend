<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MaintenancesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MaintenancesTable Test Case
 */
class MaintenancesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var MaintenancesTable
     */
    public $Maintenances;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.maintenances',
        'app.equipments',
        'app.completedmaintenances',
        'app.steps',
        'app.consumables',
        'app.parts',
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
        $config = TableRegistry::getTableLocator()->exists('Maintenances') ? [] : ['className' => MaintenancesTable::class];
        $this->Maintenances = TableRegistry::getTableLocator()->get('Maintenances', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Maintenances);

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
