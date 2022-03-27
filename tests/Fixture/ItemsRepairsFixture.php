<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ItemsRepairsFixture
 */
class ItemsRepairsFixture extends TestFixture
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
                'repair_id' => 1,
                'item_id' => 1,
                'quantity' => 1,
            ],
        ];
        parent::init();
    }
}
