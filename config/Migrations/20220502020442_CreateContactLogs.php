<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateContactLogs extends AbstractMigration
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
        $table = $this->table('contact_logs');
        $table->addColumn('when', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('spoke_to', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('details', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('user_id', 'uuid', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('incident_form_submission_id', 'integer');
        $table->create();
    }
}
