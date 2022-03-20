<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Api\V1;

use App\Controller\Api\V1\RepairsController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Api\V1\RepairsController Test Case
 *
 * @uses \App\Controller\Api\V1\RepairsController
 */
class RepairsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Repairs',
        'app.CreatedBy',
        'app.ModifiedBy',
        'app.Comments',
        'app.Subtasks',
        'app.Assignedto',
        'app.AssignedBy',
        'app.Stores',
        'app.Maintenances',
        'app.Equipments',
        'app.CompletedMaintenances',
        'app.Items',
        'app.ActivityLogs',
        'app.RepairReminders',
        'app.Files',
        'app.ItemsRepairs',
        'app.RepairsRepairs',
        'app.FilesRepairs',
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
