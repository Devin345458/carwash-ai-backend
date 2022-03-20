<?php

use Migrations\AbstractMigration;

class CreateUsers extends AbstractMigration
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
        $this->table('users', ['id' => false, 'primary_key' => ['id']])
            ->addColumn(
                'id',
                'uuid',
                [
                'default' => null,
                'limit' => null,
                'null' => false,
                ]
            )
            ->addPrimaryKey(['id'])
            ->addColumn(
                'username',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => false,
                ]
            )
            ->addColumn(
                'email',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => true,
                ]
            )
            ->addColumn(
                'password',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => false,
                ]
            )
            ->addColumn(
                'first_name',
                'string',
                [
                'default' => null,
                'limit' => 50,
                'null' => true,
                ]
            )
            ->addColumn(
                'last_name',
                'string',
                [
                'default' => null,
                'limit' => 50,
                'null' => true,
                ]
            )
            ->addColumn(
                'token',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => true,
                ]
            )
            ->addColumn(
                'token_expires',
                'datetime',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'api_token',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => true,
                ]
            )
            ->addColumn(
                'activation_date',
                'datetime',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'secret',
                'string',
                [
                'default' => null,
                'limit' => 32,
                'null' => true,
                ]
            )
            ->addColumn(
                'secret_verified',
                'boolean',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'tos_date',
                'datetime',
                [
                'default' => null,
                'limit' => null,
                'null' => true,
                ]
            )
            ->addColumn(
                'active',
                'boolean',
                [
                'default' => false,
                'limit' => null,
                'null' => false,
                ]
            )
            ->addColumn(
                'is_superuser',
                'boolean',
                [
                'default' => false,
                'limit' => null,
                'null' => false,
                ]
            )
            ->addColumn(
                'role',
                'string',
                [
                'comment' => 'Roles: user, manager, admin, employee, account_manager, developer',
                'default' => 'user',
                'limit' => 255,
                'null' => true,
                ]
            )
            ->addColumn(
                'company_id',
                'integer',
                [
                'default' => null,
                'limit' => 11,
                'null' => false,
                ]
            )
            ->addColumn(
                'active_store',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => true,
                ]
            )
            ->addColumn(
                'created',
                'datetime',
                [
                'default' => null,
                'limit' => null,
                'null' => false,
                ]
            )
            ->addColumn(
                'modified',
                'datetime',
                [
                'default' => null,
                'limit' => null,
                'null' => false,
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
            ->addColumn(
                'about',
                'string',
                [
                'default' => null,
                'limit' => 1000,
                'null' => true,
                ]
            )
            ->addColumn(
                'time_zone',
                'string',
                [
                'default' => null,
                'limit' => 255,
                'null' => true,
                ]
            )
            ->addIndex(
                [
                    'username',
                ],
                ['unique' => true]
            )
            ->addIndex(
                [
                    'company_id',
                ]
            )
            ->create();
    }
}
