<?php

use Migrations\AbstractMigration;

class CreateCategories extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * @return void
     */
    public function change()
    {
        $table = $this->table('categories');
        $table->addColumn(
            'category',
            'string',
            [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]
        );
        $table->addColumn(
            'description',
            'string',
            [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]
        );
        $table->addColumn(
            'company_id',
            'integer',
            [
            'default' => null,
            'limit' => 11,
            'null' => false,
            ]
        );
        $table->addColumn(
            'model',
            'string',
            [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]
        );
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
        $table->create();
    }
}
