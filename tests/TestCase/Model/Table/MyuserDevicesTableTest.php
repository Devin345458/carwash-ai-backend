<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MyuserDevicesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MyuserDevicesTable Test Case
 */
class MyuserDevicesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MyuserDevicesTable
     */
    public $MyuserDevices;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.MyuserDevices',
        'app.Devices',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MyuserDevices') ? [] : ['className' => MyuserDevicesTable::class];
        $this->MyuserDevices = TableRegistry::getTableLocator()->get('MyuserDevices', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MyuserDevices);

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
