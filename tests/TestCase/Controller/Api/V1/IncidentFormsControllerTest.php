<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Api\V1;

use App\Controller\Api\V1\IncidentFormsController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Api\V1\IncidentFormsController Test Case
 *
 * @uses \App\Controller\Api\V1\IncidentFormsController
 */
class IncidentFormsControllerTest extends TestCase
{
    use IntegrationTestTrait;

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
     * Test index method
     *
     * @return void
     * @uses \App\Controller\Api\V1\IncidentFormsController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\Api\V1\IncidentFormsController::view()
     */
    public function testView(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Api\V1\IncidentFormsController::add()
     */
    public function testAdd(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Api\V1\IncidentFormsController::edit()
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\Api\V1\IncidentFormsController::delete()
     */
    public function testDelete(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
