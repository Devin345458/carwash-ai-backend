<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ItemsRepairsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ItemsRepairsTable Test Case
 */
class ItemsRepairsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ItemsRepairsTable
     */
    protected $ItemsRepairs;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.ItemsRepairs',
        'app.Repairs',
        'app.Items',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ItemsRepairs') ? [] : ['className' => ItemsRepairsTable::class];
        $this->ItemsRepairs = $this->getTableLocator()->get('ItemsRepairs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->ItemsRepairs);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ItemsRepairsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\ItemsRepairsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
