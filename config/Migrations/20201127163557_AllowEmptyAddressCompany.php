<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AllowEmptyAddressCompany extends AbstractMigration
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
        $table = $this->table('companies');
        $table->changeColumn('address', 'string', [
            'length' => 500,
            'null' => true
        ]);
        $table->changeColumn('state', 'string', [
            'length' => 2,
            'null' => true
        ]);
        $table->changeColumn('country', 'string', [
            'length' => 20,
            'null' => true
        ]);
        $table->changeColumn('zipcode', 'integer', [
            'length' => 5,
            'null' => true
        ]);
        $table->changeColumn('city', 'string', [
            'length' => 255,
            'null' => true
        ]);
        $table->update();
    }
}
