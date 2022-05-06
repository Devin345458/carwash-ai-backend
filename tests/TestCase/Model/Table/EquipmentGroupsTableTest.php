<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EquipmentGroupsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EquipmentGroupsTable Test Case
 */
class EquipmentGroupsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EquipmentGroupsTable
     */
    protected $EquipmentGroups;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.EquipmentGroups',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('EquipmentGroups') ? [] : ['className' => EquipmentGroupsTable::class];
        $this->EquipmentGroups = $this->getTableLocator()->get('EquipmentGroups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->EquipmentGroups);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\EquipmentGroupsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
