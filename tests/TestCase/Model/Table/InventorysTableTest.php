<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InventorysTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InventorysTable Test Case
 */
class InventorysTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var InventorysTable
     */
    public $Inventorys;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.inventorys',
        'app.stores',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Inventorys') ? [] : ['className' => InventorysTable::class];
        $this->Inventorys = TableRegistry::getTableLocator()->get('Inventorys', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Inventorys);

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
