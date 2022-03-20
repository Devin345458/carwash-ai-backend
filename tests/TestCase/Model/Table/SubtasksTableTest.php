<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SubtasksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SubtasksTable Test Case
 */
class SubtasksTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var SubtasksTable
     */
    public $Subtasks;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.subtasks',
        'app.repairs',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Subtasks') ? [] : ['className' => SubtasksTable::class];
        $this->Subtasks = TableRegistry::getTableLocator()->get('Subtasks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Subtasks);

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
