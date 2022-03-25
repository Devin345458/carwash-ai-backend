<?php
declare(strict_types=1);

use Cake\I18n\FrozenTime;
use Migrations\AbstractSeed;

/**
 * DefaultItemTypes seed.
 */
class DefaultItemTypesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Parts',
                'company_id' => 1,
                'created' => new FrozenTime(),
                'modified' => new FrozenTime()
            ],
            [
                'name' => 'Tools',
                'company_id' => 1,
                'created' => new FrozenTime(),
                'modified' => new FrozenTime()
            ],
            [
                'name' => 'Consumables',
                'company_id' => 1,
                'created' => new FrozenTime(),
                'modified' => new FrozenTime()
            ],
            [
                'name' => 'Chemicals',
                'company_id' => 1,
                'created' => new FrozenTime(),
                'modified' => new FrozenTime()
            ],
            [
                'name' => 'Vending',
                'company_id' => 1,
                'created' => new FrozenTime(),
                'modified' => new FrozenTime()
            ]
        ];

        $table = $this->table('item_types');
        $table->insert($data)->save();
    }
}
