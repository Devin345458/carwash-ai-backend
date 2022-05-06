<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddIncidentFormsTables extends AbstractMigration
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
        $table = $this->table('incident_form_versions');
        $table->addColumn('incident_form_id', 'integer');
        $table->addColumn('version', 'integer');
        $table->addColumn('data', 'text');
        $table->addColumn('created', 'timestamp');
        $table->create();

        $table = $this->table('incident_forms');
        $table->addColumn('name', 'string');
        $table->addColumn('incident_form_version_id', 'integer');
        $table->addColumn('store_id', 'uuid');
        $table->addColumn('created', 'timestamp');
        $table->addColumn('modified', 'timestamp');
        $table->create();

        $table = $this->table('incident_form_submissions');
        $table->addColumn('data', 'text');
        $table->addColumn('incident_form_version_id', 'integer');
        $table->addColumn('user_id', 'uuid');
        $table->addColumn('created', 'timestamp');
        $table->addColumn('modified', 'timestamp');
        $table->create();;
    }
}
