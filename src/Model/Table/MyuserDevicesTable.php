<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MyuserDevices Model
 *
 * @property \App\Model\Table\DevicesTable|\Cake\ORM\Association\BelongsTo $Devices
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @method \App\Model\Entity\MyuserDevice get($primaryKey, $options = [])
 * @method \App\Model\Entity\MyuserDevice newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MyuserDevice[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MyuserDevice|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MyuserDevice saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MyuserDevice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MyuserDevice[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MyuserDevice findOrCreate($search, callable $callback = null, $options = [])
 */
class MyuserDevicesTable extends Table
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

        $this->setTable('myuser_devices');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo(
            'Users',
            [
            'foreignKey' => 'myuser_id',
            'joinType' => 'INNER',
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

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param  \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['myuser_id'], 'Users'));

        return $rules;
    }
}
