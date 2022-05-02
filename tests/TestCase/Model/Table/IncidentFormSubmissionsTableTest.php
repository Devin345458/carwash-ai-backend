<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\IncidentFormSubmissionsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\IncidentFormSubmissionsTable Test Case
 */
class IncidentFormSubmissionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\IncidentFormSubmissionsTable
     */
    protected $IncidentFormSubmissions;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.IncidentFormSubmissions',
        'app.IncidentFormVersions',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('IncidentFormSubmissions') ? [] : ['className' => IncidentFormSubmissionsTable::class];
        $this->IncidentFormSubmissions = $this->getTableLocator()->get('IncidentFormSubmissions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->IncidentFormSubmissions);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\IncidentFormSubmissionsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\IncidentFormSubmissionsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
