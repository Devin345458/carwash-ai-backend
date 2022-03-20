<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersStoresTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersStoresTable Test Case
 */
class UsersStoresTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var UsersStoresTable
     */
    public $UsersStores;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Users_stores',
        'app.stores',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UsersStores') ? [] : ['className' => UsersStoresTable::class];
        $this->UsersStores = TableRegistry::getTableLocator()->get('UsersStores', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsersStores);

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
