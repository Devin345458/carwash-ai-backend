<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ContactLogsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ContactLogsTable Test Case
 */
class ContactLogsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ContactLogsTable
     */
    protected $ContactLogs;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.ContactLogs',
        'app.Users',
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
        $config = $this->getTableLocator()->exists('ContactLogs') ? [] : ['className' => ContactLogsTable::class];
        $this->ContactLogs = $this->getTableLocator()->get('ContactLogs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->ContactLogs);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ContactLogsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\ContactLogsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
