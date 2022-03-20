<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Error\Exception\ValidationException;
use App\Model\Entity\User;
use ArrayObject;
use Cake\Database\Query;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Exception;

/**
 * Users Model
 *
 * @property CompaniesTable&BelongsTo $Companies
 * @property FilesTable&BelongsTo $Photos
 * @property StoresTable&BelongsToMany $Stores
 * @method User newEmptyEntity()
 * @method User newEntity(array $data, array $options = [])
 * @method User[] newEntities(array $data, array $options = [])
 * @method User get($primaryKey, $options = [])
 * @method User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method User patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method User|false save(EntityInterface $entity, $options = [])
 * @method User saveOrFail(EntityInterface $entity, $options = [])
 * @method User[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method User[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method User[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method User[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin TimestampBehavior
 */
class UsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsToMany('Stores');

        $this->belongsTo('Files', [
            'foreignKey' => 'file_id',
        ]);
        $this->hasMany('Notifications', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('UsersDevices', [
            'foreignKey' => 'user_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->uuid('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->scalar('first_name')
            ->maxLength('first_name', 50)
            ->allowEmptyString('first_name');

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 50)
            ->allowEmptyString('last_name');

        $validator
            ->scalar('token')
            ->maxLength('token', 255)
            ->allowEmptyString('token');

        $validator
            ->dateTime('token_expires')
            ->allowEmptyDateTime('token_expires');

        $validator
            ->scalar('api_token')
            ->maxLength('api_token', 255)
            ->allowEmptyString('api_token');

        $validator
            ->dateTime('activation_date')
            ->allowEmptyDateTime('activation_date');

        $validator
            ->scalar('secret')
            ->maxLength('secret', 32)
            ->allowEmptyString('secret');

        $validator
            ->boolean('secret_verified')
            ->allowEmptyString('secret_verified');

        $validator
            ->dateTime('tos_date')
            ->allowEmptyDateTime('tos_date');

        $validator
            ->boolean('active')
            ->notEmptyString('active');

        $validator
            ->boolean('is_superuser')
            ->notEmptyString('is_superuser');

        $validator
            ->scalar('role')
            ->maxLength('role', 255)
            ->allowEmptyString('role');

        $validator
            ->scalar('active_store')
            ->maxLength('active_store', 255)
            ->allowEmptyString('active_store');

        $validator
            ->scalar('about')
            ->maxLength('about', 1000)
            ->allowEmptyString('about');

        $validator
            ->scalar('time_zone')
            ->maxLength('time_zone', 255)
            ->allowEmptyString('time_zone');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['username']), ['errorField' => 'username']);
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);
        $rules->add($rules->existsIn(['company_id'], 'Companies'), ['errorField' => 'company_id']);
        $rules->add($rules->existsIn(['file_id'], 'Files'), ['errorField' => 'file_id']);

        return $rules;
    }

    /**
     * @param Query $query
     * @param array $options
     * @return Query
     */
    public function findAuth(Query $query, array $options)
    {
        return $query;
    }

    /**
     * @param EventInterface $event Before Save Event
     * @param User $user The user entity
     * @param ArrayObject $options The options
     * @return void
     */
    public function beforeSave(EventInterface $event, User $user, ArrayObject $options)
    {
        if ($user->isNew()) {
            $user->active_store = 0;
        }
    }

    /**
     * @param array $data The request data
     * @throws Exception
     * @return User
     */
    public function register(array $data): User
    {
        return $this->getConnection()->transactional(function () use ($data) {
            $company = $this->Companies->newEntity([
                'name' => $data['company_name'],
                'email' => $data['email'],
                'billing_last_name' => $data['first_name'],
                'billing_first_name' => $data['last_name'],
            ]);

            if (!$this->Companies->save($company)) {
                throw new ValidationException($company);
            }

            $user = $this->newEntity([
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'role' => 'admin',
                'password' => $data['password'],
                'company_id' => $company->id,
            ]);

            if (!$this->save($user)) {
                throw new ValidationException($user);
            }

            $store = $this->Stores->newEntity([
                'name' => 'Store 1',
                'plan_id' => $data['plan_id'],
                'store_type_id' => 1,
                'company_id' => $company->id,
            ]);

            if (!$this->Stores->save($store)) {
                throw new ValidationException($store);
            }

            $this->Stores->link($user, [$store]);

            return $user;
        });
    }
}
