<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CompanysTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CompanysTable Test Case
 */
class CompanysTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var CompanysTable
     */
    public $Companys;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.companys',
        'app.consumables',
        'app.parts',
        'app.stores',
        'app.tools',
        'app.users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Companys') ? [] : ['className' => CompanysTable::class];
        $this->Companys = TableRegistry::getTableLocator()->get('Companys', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Companys);

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
