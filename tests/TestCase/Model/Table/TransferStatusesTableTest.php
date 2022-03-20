<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TransferStatusesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TransferStatusesTable Test Case
 */
class TransferStatusesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TransferStatusesTable
     */
    public $TransferStatuses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.TransferStatuses',
        'app.TransferRequests',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('TransferStatuses') ? [] : ['className' => TransferStatusesTable::class];
        $this->TransferStatuses = TableRegistry::getTableLocator()->get('TransferStatuses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TransferStatuses);

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
}
