<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MaintenanceSessionsMaintenancesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MaintenanceSessionsMaintenancesTable Test Case
 */
class MaintenanceSessionsMaintenancesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MaintenanceSessionsMaintenancesTable
     */
    protected $MaintenanceSessionsMaintenances;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.MaintenanceSessionsMaintenances',
        'app.Maintenances',
        'app.MaintenanceSessions',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('MaintenanceSessionsMaintenances') ? [] : ['className' => MaintenanceSessionsMaintenancesTable::class];
        $this->MaintenanceSessionsMaintenances = $this->getTableLocator()->get('MaintenanceSessionsMaintenances', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->MaintenanceSessionsMaintenances);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
