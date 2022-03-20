<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StoresUsersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StoresUsersTable Test Case
 */
class StoresUsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\StoresUsersTable
     */
    protected $StoresUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.StoresUsers',
        'app.Stores',
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
        $config = $this->getTableLocator()->exists('StoresUsers') ? [] : ['className' => StoresUsersTable::class];
        $this->StoresUsers = $this->getTableLocator()->get('StoresUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->StoresUsers);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
