<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\IncidentFormVersionsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\IncidentFormVersionsTable Test Case
 */
class IncidentFormVersionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\IncidentFormVersionsTable
     */
    protected $IncidentFormVersions;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.IncidentFormVersions',
        'app.IncidentForms',
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
        $config = $this->getTableLocator()->exists('IncidentFormVersions') ? [] : ['className' => IncidentFormVersionsTable::class];
        $this->IncidentFormVersions = $this->getTableLocator()->get('IncidentFormVersions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->IncidentFormVersions);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\IncidentFormVersionsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\IncidentFormVersionsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
