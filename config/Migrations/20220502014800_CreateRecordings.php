<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateRecordings extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('recordings');
        $table->addColumn('camera', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('start_time', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('end_time', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('incident_form_submission_id', 'integer');
        $table->addColumn('created', 'timestamp');
        $table->addColumn('modified', 'timestamp');
        $table->create();
    }
}
