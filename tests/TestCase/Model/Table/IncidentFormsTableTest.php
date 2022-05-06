<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\IncidentFormsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\IncidentFormsTable Test Case
 */
class IncidentFormsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\IncidentFormsTable
     */
    protected $IncidentForms;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.IncidentForms',
        'app.Stores',
        'app.IncidentFormVersions',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('IncidentForms') ? [] : ['className' => IncidentFormsTable::class];
        $this->IncidentForms = $this->getTableLocator()->get('IncidentForms', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->IncidentForms);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\IncidentFormsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\IncidentFormsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
