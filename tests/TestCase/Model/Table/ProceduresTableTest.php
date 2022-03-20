<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProceduresTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProceduresTable Test Case
 */
class ProceduresTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var ProceduresTable
     */
    public $Procedures;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.procedures',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Procedures') ? [] : ['className' => ProceduresTable::class];
        $this->Procedures = TableRegistry::getTableLocator()->get('Procedures', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Procedures);

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
     * Test getStoresProcedures method
     *
     * @return void
     */
    public function testGetStoresProcedures()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
