<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ClaimsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ClaimsTable Test Case
 */
class ClaimsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ClaimsTable
     */
    public $Claims;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Claims',
        'app.InsuranceCards',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Claims') ? [] : ['className' => ClaimsTable::class];
        $this->Claims = TableRegistry::getTableLocator()->get('Claims', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Claims);

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
