<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * IncidentFormsFixture
 */
class IncidentFormsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'version' => 1,
                'store_id' => '24b586d4-9a16-4236-9dfc-0408526169fd',
                'created' => 1651286854,
                'updated' => 1651286854,
            ],
        ];
        parent::init();
    }
}
