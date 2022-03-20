<?php
namespace App\Model\Table;

use App\Model\Entity\Item;
use ArrayObject;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Routing\Router;
use Cake\Validation\Validator;

/**
 * Items Model
 *
 * @property ItemTypesTable|BelongsTo $ItemTypes
 * @property CompaniesTable|BelongsTo $Companies
 * @property FilesTable|BelongsTo $Photos
 * @property InventoriesTable|HasMany $Inventories
 * @method Item get($primaryKey, $options = [])
 * @method Item newEntity($data = null, array $options = [])
 * @method Item[] newEntities(array $data, array $options = [])
 * @method Item|bool save(EntityInterface $entity, $options = [])
 * @method Item saveOrFail(EntityInterface $entity, $options = [])
 * @method Item patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Item[] patchEntities($entities, array $data, array $options = [])
 * @method Item findOrCreate($search, callable $callback = null, $options = [])
 * @mixin TimestampBehavior
 */
class ItemsTable extends Table
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

        $this->setTable('items');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('CompanyScope');

        $this->belongsTo(
            'ItemTypes',
            [
            'foreignKey' => 'item_type_id',
            'joinType' => 'INNER',
            ]
        );
        $this->belongsTo(
            'Companies',
            [
            'foreignKey' => 'company_id',
            ]
        );
        $this->belongsTo(
            'Files',
            [
            'foreignKey' => 'file_id',
            ]
        );
        $this->hasMany(
            'Inventories',
            [
            'foreignKey' => 'item_id',
            ]
        );

        $this->hasOne(
            'ActiveStoreInventories',
            [
            'foreignKey' => 'item_id',
            'conditions' => ['ActiveStoreInventories.store_id' => Router::getRequest() ? Router::getRequest()->getAttribute('identity')->active_store : null],
            'className' => 'Inventories',
            ]
        );
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
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->uuid('created_by')
            ->allowEmptyString('created_by');

        $validator
            ->uuid('modified_by')
            ->allowEmptyString('modified_by');

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
        $rules->add($rules->existsIn(['item_type_id'], 'ItemTypes'));
        $rules->add($rules->existsIn(['company_id'], 'Companies'));
        $rules->add($rules->existsIn(['file_id'], 'Files'));

        return $rules;
    }
}
