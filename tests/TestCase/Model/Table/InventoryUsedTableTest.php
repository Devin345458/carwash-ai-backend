<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InventoryUsesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InventoryUsesTable Test Case
 */
class InventoryUsedTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var InventoryUsesTable
     */
    public $InventoryUsed;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.inventory_used',
        'app.stores',
        'app.inventorys',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('InventoryUsed') ? [] : ['className' => InventoryUsesTable::class];
        $this->InventoryUsed = TableRegistry::getTableLocator()->get('InventoryUsed', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InventoryUsed);

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
