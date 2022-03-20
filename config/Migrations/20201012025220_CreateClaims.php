<?php

use Migrations\AbstractMigration;

class CreateClaims extends AbstractMigration
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
        $table = $this->table('claims');
        $table->addColumn(
            'customer_first_name',
            'string',
            [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]
        );
        $table->addColumn(
            'customer_last_name',
            'string',
            [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]
        );
        $table->addColumn(
            'incident_datetime',
            'datetime',
            [
            'default' => null,
            'null' => false,
            ]
        );
        $table->addColumn(
            'report_time',
            'datetime',
            [
            'default' => null,
            'null' => false,
            ]
        );
        $table->addColumn(
            'service',
            'integer',
            [
            'default' => null,
            'limit' => 11,
            'null' => false,
            ]
        );
        $table->addColumn(
            'extras',
            'string',
            [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]
        );
        $table->addColumn(
            'pay_station',
            'integer',
            [
            'default' => null,
            'limit' => 11,
            'null' => false,
            ]
        );
        $table->addColumn(
            'customer_address',
            'integer',
            [
            'default' => null,
            'limit' => 11,
            'null' => false,
            ]
        );
        $table->addColumn(
            'customer_city',
            'string',
            [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]
        );
        $table->addColumn(
            'customer_state',
            'string',
            [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]
        );
        $table->addColumn(
            'customer_zipcode',
            'string',
            [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]
        );
        $table->addColumn(
            'customer_email',
            'string',
            [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]
        );
        $table->addColumn(
            'insurance_card_id',
            'integer',
            [
            'default' => null,
            'limit' => 11,
            'null' => false,
            ]
        );
        $table->addColumn(
            'make',
            'string',
            [
            'default' => null,
            'limit' => 255,
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
            'year',
            'integer',
            [
            'default' => null,
            'limit' => 11,
            'null' => false,
            ]
        );
        $table->addColumn(
            'color',
            'string',
            [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]
        );
        $table->addColumn(
            'vin',
            'string',
            [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]
        );
        $table->addColumn(
            'mileage',
            'integer',
            [
            'default' => null,
            'limit' => 11,
            'null' => false,
            ]
        );
        $table->addColumn(
            'plate',
            'string',
            [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]
        );
        $table->addColumn(
            'plate_state',
            'string',
            [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]
        );
        $table->addColumn(
            'affected_area_of_vehicle',
            'text',
            [
            'default' => null,
            'null' => false,
            ]
        );
        $table->addColumn(
            'customer_statement',
            'text',
            [
            'default' => null,
            'null' => false,
            ]
        );
        $table->addColumn(
            'prior_damage',
            'boolean',
            [
            'default' => null,
            'null' => false,
            ]
        );
        $table->addColumn(
            'prior_damage_description',
            'text',
            [
            'default' => null,
            'null' => false,
            ]
        );
        $table->addColumn(
            'employee_notes',
            'text',
            [
            'default' => null,
            'null' => false,
            ]
        );
        $table->create();
    }
}
