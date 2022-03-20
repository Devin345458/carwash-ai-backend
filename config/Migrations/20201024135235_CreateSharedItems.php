<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateSharedItems extends AbstractMigration
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
        $table = $this->table('shared_items')
            ->addColumn(
                'name',
                'string',
                [
                    'default' => null,
                    'limit' => 255,
                    'null' => false,
                ]
            )
            ->addColumn(
                'item_type_id',
                'integer',
                [
                    'default' => null,
                    'limit' => 11,
                    'null' => false,
                ]
            )
            ->addColumn(
                'description',
                'text',
                [
                    'default' => null,
                    'limit' => null,
                    'null' => true,
                ]
            )
            ->addColumn(
                'company_id',
                'integer',
                [
                    'default' => null,
                    'limit' => 11,
                    'null' => true,
                ]
            )
            ->addColumn(
                'created',
                'datetime',
                [
                    'default' => null,
                    'limit' => null,
                    'null' => true,
                ]
            )
            ->addColumn(
                'modified',
                'datetime',
                [
                    'default' => null,
                    'limit' => null,
                    'null' => true,
                ]
            )
            ->addColumn(
                'created_by',
                'uuid',
                [
                    'default' => null,
                    'limit' => null,
                    'null' => true,
                ]
            )
            ->addColumn(
                'modified_by',
                'uuid',
                [
                    'default' => null,
                    'limit' => null,
                    'null' => true,
                ]
            )
            ->addColumn(
                'photo_id',
                'integer',
                [
                    'default' => null,
                    'limit' => 11,
                    'null' => true,
                ]
            )
            ->addIndex(
                [
                    'name',
                    'description',
                ],
                ['type' => 'fulltext']
            );
        $table->create();
    }
}
