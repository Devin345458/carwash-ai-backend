<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RecordingsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RecordingsTable Test Case
 */
class RecordingsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RecordingsTable
     */
    protected $Recordings;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Recordings',
        'app.IncidentFormSubmissions',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Recordings') ? [] : ['className' => RecordingsTable::class];
        $this->Recordings = $this->getTableLocator()->get('Recordings', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Recordings);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\RecordingsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\RecordingsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
