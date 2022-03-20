<?php
namespace App\Model\Table;

use App\Model\Entity\Myuser;
use App\Model\Entity\Tour;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tours Model
 *
 * @property UsersTable|BelongsTo $Users
 * @method Tour get($primaryKey, $options = [])
 * @method Tour newEntity($data = null, array $options = [])
 * @method Tour[] newEntities(array $data, array $options = [])
 * @method Tour|bool save(EntityInterface $entity, $options = [])
 * @method Tour|bool saveOrFail(EntityInterface $entity, $options = [])
 * @method Tour patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Tour[] patchEntities($entities, array $data, array $options = [])
 * @method Tour findOrCreate($search, callable $callback = null, $options = [])
 * @mixin TimestampBehavior
 */
class ToursTable extends Table
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

        $this->setTable('tours');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo(
            'Users',
            [
            'foreignKey' => 'Users_id',
            'joinType' => 'INNER',
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param  Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param  RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['Users_id'], 'Users'));

        return $rules;
    }

    public function findUsersCompletedTutorials(Query $query, $user)
    {
        return $query->find('All')->where(['Users_id' => $user['id']])->select('name')->toArray();
    }
}
