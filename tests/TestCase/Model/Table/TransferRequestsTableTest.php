<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TransferRequestsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TransferRequestsTable Test Case
 */
class TransferRequestsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TransferRequestsTable
     */
    public $TransferRequests;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.TransferRequests',
        'app.ToStores',
        'app.FromStores',
        'app.TransferStatuses',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('TransferRequests') ? [] : ['className' => TransferRequestsTable::class];
        $this->TransferRequests = TableRegistry::getTableLocator()->get('TransferRequests', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TransferRequests);

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
