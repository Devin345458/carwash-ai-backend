<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OrderItemsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OrderitemsTable Test Case
 */
class OrderitemsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var OrderItemsTable
     */
    public $Orderitems;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Orderitems',
        'app.Orders',
        'app.Inventories',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Orderitems') ? [] : ['className' => OrderItemsTable::class];
        $this->Orderitems = TableRegistry::getTableLocator()->get('Orderitems', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Orderitems);

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
