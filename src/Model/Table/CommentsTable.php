<?php
namespace App\Model\Table;

use App\Model\Entity\Comment;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Comments Model
 *
 * @property UsersTable|BelongsTo $Users
 * @property RepairsTable|BelongsTo $Repairs
 * @method Comment get($primaryKey, $options = [])
 * @method Comment newEntity($data = null, array $options = [])
 * @method Comment[] newEntities(array $data, array $options = [])
 * @method Comment|bool save(EntityInterface $entity, $options = [])
 * @method Comment|bool saveOrFail(EntityInterface $entity, $options = [])
 * @method Comment patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Comment[] patchEntities($entities, array $data, array $options = [])
 * @method Comment findOrCreate($search, callable $callback = null, $options = [])
 * @mixin TimestampBehavior
 */
class CommentsTable extends Table
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

        $this->setTable('comments');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior('WhoDidIt');

        $this->hasMany('ActivityLogs')
            ->setConditions(['object_model' => 'Comments'])
            ->setForeignKey('foreign_key')
            ->setBindingKey('id');
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
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('content')
            ->maxLength('content', 255)
            ->allowEmptyString('content', null,false);

        $validator
            ->dateTime('created')
            ->allowEmptyDateTime('created', null, 'create');

        $validator
            ->dateTime('modified')
            ->allowEmptyDateTime('modified', null, 'create');

        $validator->integer('commentable_id')
            ->allowEmptyString('commentable_id', null,false)
            ->requirePresence('commentable_id');

        $validator->scalar('commentable_type')
            ->maxLength('commentable_type', 255)
            ->allowEmptyString('commentable_type', null,false)
            ->requirePresence('commentable_type');

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
        $rules->add($rules->existsIn(['created_by_id'], 'CreatedBy', 'The user you created this with no longer exists'));
        $rules->add($rules->existsIn(['modified_by_id'], 'ModifiedBy', 'The user you modified this with no longer exists'));

        return $rules;
    }
}
