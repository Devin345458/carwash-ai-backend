<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FilesRepairsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PhotosRepairsTable Test Case
 */
class PhotosRepairsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FilesRepairsTable
     */
    public $PhotosRepairs;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PhotosRepairs',
        'app.Photos',
        'app.Repairs',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PhotosRepairs') ? [] : ['className' => FilesRepairsTable::class];
        $this->PhotosRepairs = TableRegistry::getTableLocator()->get('PhotosRepairs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PhotosRepairs);

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
