<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StoreTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StoreTypesTable Test Case
 */
class StoreTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\StoreTypesTable
     */
    public $StoreTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.StoreTypes',
        'app.Stores',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('StoreTypes') ? [] : ['className' => StoreTypesTable::class];
        $this->StoreTypes = TableRegistry::getTableLocator()->get('StoreTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->StoreTypes);

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
