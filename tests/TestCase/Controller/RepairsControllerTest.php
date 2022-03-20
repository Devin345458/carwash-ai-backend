<?php
namespace App\Test\TestCase\Controller;

use App\Controller\RepairsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\RepairsController Test Case
 */
class RepairsControllerTest extends IntegrationTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.repairs',
        'app.users',
        'app.stores',
        'app.equipments',
        'app.subtasks',
        'app.consumables',
        'app.parts',
        'app.tools',
        'app.consumables_repairs',
        'app.parts_repairs',
        'app.repairs_tools',
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
