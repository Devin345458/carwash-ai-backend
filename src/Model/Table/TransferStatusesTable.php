<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TransferStatuses Model
 *
 * @property \App\Model\Table\TransferRequestsTable|\Cake\ORM\Association\HasMany $TransferRequests
 * @method \App\Model\Entity\TransferStatus get($primaryKey, $options = [])
 * @method \App\Model\Entity\TransferStatus newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TransferStatus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TransferStatus|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TransferStatus saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TransferStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TransferStatus[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TransferStatus findOrCreate($search, callable $callback = null, $options = [])
 */
class TransferStatusesTable extends Table
{
    /**
     * Initialize method
     *
     * @param  array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('transfer_statuses');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany(
            'TransferRequests',
            [
            'foreignKey' => 'transfer_status_id',
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param  \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        return $validator;
    }
}
