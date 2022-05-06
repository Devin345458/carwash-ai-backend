<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RecordingsFixture
 */
class RecordingsFixture extends TestFixture
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
                'camera' => 'Lorem ipsum dolor sit amet',
                'start_time' => 'Lorem ipsum dolor sit amet',
                'end_time' => 'Lorem ipsum dolor sit amet',
                'incident_form_submission_id' => 'Lorem ipsum dolor sit amet',
                'created' => 1651456132,
                'modified' => 1651456132,
            ],
        ];
        parent::init();
    }
}
