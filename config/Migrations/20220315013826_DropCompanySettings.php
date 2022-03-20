<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class DropCompanySettings extends AbstractMigration
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
        $table = $this->table('company_settings');
        $table->drop();
        $table->update();
        $table = $this->table('companies');
        $table->addColumn('allow_car_count', 'boolean');
        $table->update();
    }
}
