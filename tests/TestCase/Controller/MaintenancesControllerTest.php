<?php
namespace App\Test\TestCase\Controller;

use App\Controller\MaintenancesController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\MaintenancesController Test Case
 */
class MaintenancesControllerTest extends IntegrationTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.maintenances',
        'app.equipments',
        'app.steps',
        'app.consumables',
        'app.parts',
        'app.tools',
        'app.consumables_maintenances',
        'app.maintenances_parts',
        'app.maintenances_tools',
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
