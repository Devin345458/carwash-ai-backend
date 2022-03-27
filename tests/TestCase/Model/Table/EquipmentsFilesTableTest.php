<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EquipmentsFilesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EquipmentsFilesTable Test Case
 */
class EquipmentsFilesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EquipmentsFilesTable
     */
    protected $EquipmentsFiles;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.EquipmentsFiles',
        'app.Equipment',
        'app.Files',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('EquipmentsFiles') ? [] : ['className' => EquipmentsFilesTable::class];
        $this->EquipmentsFiles = $this->getTableLocator()->get('EquipmentsFiles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->EquipmentsFiles);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\EquipmentsFilesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\EquipmentsFilesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
