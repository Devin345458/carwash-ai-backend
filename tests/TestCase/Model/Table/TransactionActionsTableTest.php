<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TransactionActionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TransactionActionsTable Test Case
 */
class TransactionActionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TransactionActionsTable
     */
    public $TransactionActions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.TransactionActions',
        'app.InventoryTransactions',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('TransactionActions') ? [] : ['className' => TransactionActionsTable::class];
        $this->TransactionActions = TableRegistry::getTableLocator()->get('TransactionActions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TransactionActions);

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
