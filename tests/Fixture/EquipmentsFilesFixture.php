<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EquipmentsFilesFixture
 */
class EquipmentsFilesFixture extends TestFixture
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
                'equipment_id' => 1,
                'file_id' => 1,
                'created_at' => 1648243137,
                'updated_at' => 1648243137,
            ],
        ];
        parent::init();
    }
}
