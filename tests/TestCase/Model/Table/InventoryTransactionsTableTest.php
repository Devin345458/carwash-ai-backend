<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InventoryTransactionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InventoryTransactionsTable Test Case
 */
class InventoryTransactionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\InventoryTransactionsTable
     */
    public $InventoryTransactions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.InventoryTransactions',
        'app.TransactionActions',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('InventoryTransactions') ? [] : ['className' => InventoryTransactionsTable::class];
        $this->InventoryTransactions = TableRegistry::getTableLocator()->get('InventoryTransactions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InventoryTransactions);

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
