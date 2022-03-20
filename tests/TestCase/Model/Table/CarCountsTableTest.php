<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CarCountsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CarcountsTable Test Case
 */
class CarCountsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var CarCountsTable
     */
    public $Carcounts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Carcounts',
        'app.Stores',
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
        $config = TableRegistry::getTableLocator()->exists('Carcounts') ? [] : ['className' => CarCountsTable::class];
        $this->Carcounts = TableRegistry::getTableLocator()->get('Carcounts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Carcounts);

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
