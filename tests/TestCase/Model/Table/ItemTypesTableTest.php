<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ItemTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ItemTypesTable Test Case
 */
class ItemTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ItemTypesTable
     */
    public $ItemTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ItemTypes',
        'app.Companies',
        'app.Items',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ItemTypes') ? [] : ['className' => ItemTypesTable::class];
        $this->ItemTypes = TableRegistry::getTableLocator()->get('ItemTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ItemTypes);

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
