<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddFieldsToEquipment extends AbstractMigration
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
        $table = $this->table('equipments');
        $table->addColumn('purchase_date', 'date', [
            'null' => true,
        ]);
        $table->addColumn('install_date', 'date', [
            'null' => true,
        ]);
        $table->addColumn('installer', 'string', [
            'null' => true,
        ]);
        $table->addColumn('warranty_expiration', 'date', [
            'null' => true,
        ]);
        $table->addColumn('model_number', 'string', [
            'null' => true,
        ]);
        $table->update();

        $table = $this->table('equipments_files');
        $table->addColumn('equipment_id', 'integer');
        $table->addColumn('file_id', 'integer');
        $table->addTimestamps();
        $table->create();
    }
}
