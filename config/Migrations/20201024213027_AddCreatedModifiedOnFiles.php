<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddCreatedModifiedOnFiles extends AbstractMigration
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
        $table = $this->table('files');
        $table->addColumn(
            'created',
            'datetime',
            [
                'default' => null,
                'null' => false,
            ]
        );
        $table->addColumn(
            'modified',
            'datetime',
            [
                'default' => null,
                'null' => false,
            ]
        );
        $table->addColumn(
            'created_by',
            'uuid',
            [
                'default' => null,
                'null' => false,
            ]
        );
        $table->addColumn(
            'modified_by',
            'uuid',
            [
                'default' => null,
                'null' => false,
            ]
        );
        $table->update();
    }
}
