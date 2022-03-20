<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateSharedEquipments extends AbstractMigration
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
        $table = $this->table('shared_equipments');
        $table->addColumn(
            'name',
            'string',
            [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ]
        );
        $table->addColumn(
            'installed_car_count',
            'integer',
            [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ]
        );
        $table->addColumn(
            'photo_id',
            'integer',
            [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ]
        );
        $table->addColumn(
            'position',
            'integer',
            [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ]
        );
        $table->addColumn(
            'location_id',
            'integer',
            [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ]
        );
        $table->addColumn(
            'store_id',
            'integer',
            [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ]
        );
        $table->addColumn(
            'manufacturer_id',
            'integer',
            [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ]
        );
        $table->addColumn(
            'created_from_id',
            'integer',
            [
                'default' => null,
                'limit' => 11,
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
        $table->addColumn('status', 'enum', [
           'values' => ['pending', 'accepted', 'denied']
        ]);
        $table->create();
    }
}
