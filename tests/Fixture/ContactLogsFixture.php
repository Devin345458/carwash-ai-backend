<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ContactLogsFixture
 */
class ContactLogsFixture extends TestFixture
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
                'when' => '2022-05-02 02:05:40',
                'spoke_to' => 'Lorem ipsum dolor sit amet',
                'details' => 'Lorem ipsum dolor sit amet',
                'user_id' => 'e6d0e5ec-d8fc-4a79-9adf-bd074ade9c1e',
                'incident_form_submission_id' => 1,
            ],
        ];
        parent::init();
    }
}
