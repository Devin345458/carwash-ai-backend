<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MaintenanceSessionsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MaintenanceSessionsTable Test Case
 */
class MaintenanceSessionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MaintenanceSessionsTable
     */
    protected $MaintenanceSessions;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.MaintenanceSessions',
        'app.CreatedBies',
        'app.Stores',
        'app.Maintenances',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('MaintenanceSessions') ? [] : ['className' => MaintenanceSessionsTable::class];
        $this->MaintenanceSessions = $this->getTableLocator()->get('MaintenanceSessions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->MaintenanceSessions);

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
