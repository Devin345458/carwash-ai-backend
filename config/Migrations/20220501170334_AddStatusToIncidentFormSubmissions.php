<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddStatusToIncidentFormSubmissions extends AbstractMigration
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
        $table = $this->table('incident_form_submissions');
        $table->addColumn('status', 'enum', [
            'default' => 'received',
            'values' => [
                'received',
                'reviewing',
                'contacting_client',
                'getting_quote',
                'denied',
                'accepted',
            ],
            'null' => false,
        ]);
        $table->addColumn('store_id', 'uuid', [
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('progress', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
        ]);
        $table->update();
    }
}
