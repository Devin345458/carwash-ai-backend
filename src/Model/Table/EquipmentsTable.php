<?php
namespace App\Model\Table;

use ADmad\Sequence\Model\Behavior\SequenceBehavior;
use App\Model\Entity\Equipment;
use Aws\S3\S3Client;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\RepositoryInterface;
use Cake\Event\EventInterface;
use Cake\Log\Log;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Association\hasMany;
use Cake\ORM\Association\HasOne;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Imagick\Imagine;
use Josegonzalez\Upload\Validation\UploadValidation;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

/**
 * Equipments Model
 *
 * @property StoresTable|BelongsTo $Stores
 * @property MaintenancesTable|HasMany $Maintenances
 * @property RepairsTable|HasMany $Repairs
 * @property CategoriesTable|HasOne $category
 * @property CategoriesTable|BelongsToMany $Categories
 * @property CommentsTable|HasMany $Comments
 * @property CompaniesTable|BelongsTo $Companies
 * @property SuppliersTable|BelongsTo $Manufacturer
 * @property LocationsTable|BelongsTo $Locations
 * @property FilesTable|BelongsTo $Files
 * @property ActivityLogsTable|BelongsTo $ActivityLogs
 * @method Equipment get($primaryKey, $options = [])
 * @method Equipment newEntity($data = null, array $options = [])
 * @method Equipment[] newEntities(array $data, array $options = [])
 * @method Equipment|bool save(EntityInterface $entity, $options = [])
 * @method Equipment|bool saveOrFail(EntityInterface $entity, $options = [])
 * @method Equipment patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Equipment[] patchEntities($entities, array $data, array $options = [])
 * @method Equipment findOrCreate($search, callable $callback = null, $options = [])
 * @method Query findById(int $id)
 * @mixin SequenceBehavior
 */
class EquipmentsTable extends Table
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

        $this->setTable('equipments');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior('WhoDidIt', [
            'userModel' => 'Users',
            'contain' => false,
        ]);

        $this->addBehavior('ADmad/Sequence.Sequence', [
            'order' => 'position', // Field to use to store integer sequence. Default "position".
            'scope' => ['location_id', 'store_id'], // Array of field names to use for grouping records. Default [].
            'start' => 1, // Initial value for sequence. Default 1.
        ]);

        $this->belongsTo('Stores');
        $this->belongsTo('DisplayImage')
            ->setForeignKey('file_id')
            ->setClassName('Files')
            ->setProperty('file');

        $this->belongsToMany('Files');
        $this->belongsTo('Locations');
        $this->belongsTo('Manufacturers', [
            'foreignKey' => 'manufacturer_id',
            'joinType' => 'LEFT',
            'className' => 'Suppliers',
        ]);
        $this->belongsTo('Companies', [
            'through' => 'Stores',
        ]);

        $this->belongsToMany('Categories');

        $this->hasMany('Maintenances');
        $this->hasMany('Repairs');
        $this->hasMany('Comments')
            ->setConditions(['commentable_type' => get_class($this)])
            ->setForeignKey('commentable_id')
            ->setBindingKey('id');

        $this->hasMany('ActivityLogs')
            ->setConditions(['object_model' => 'Equipments'])
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
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name', false);

        $validator
            ->scalar('type')
            ->maxLength('type', 255)
            ->allowEmptyString('type', true);

        $validator
            ->integer('size')
            ->allowEmptyString('size', true);

        $validator
            ->scalar('dir')
            ->maxLength('dir', 255)
            ->allowEmptyString('dir', true);

        $validator
            ->integer('position')
            ->allowEmptyString('position', false);

        $validator
            ->integer('location_id')
            ->allowEmptyString('location_id', false);

        $validator
            ->uuid('store_id')
            ->allowEmptyString('store_id', false);

        $validator
            ->integer('manufacturer_id')
            ->allowEmptyString('manufacturer_id', false);

        $validator
            ->integer('created_from_id')
            ->allowEmptyString('created_from_id', true);

        $validator
            ->dateTime('created')
            ->allowEmptyDateTime('created', 'create');

        $validator
            ->dateTime('modified')
            ->allowEmptyDateTime('modified', 'create');

        $validator
            ->scalar('created_by')
            ->maxLength('created_by', 36)
            ->minLength('created_by', 36)
            ->allowEmptyString('created_by', 'create');

        $validator
            ->scalar('modified_by')
            ->maxLength('modified_by', 36)
            ->minLength('modified_by', 36)
            ->allowEmptyString('modified_by', 'create');

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
        $rules->add($rules->existsIn(['store_id'], 'Stores'));
        $rules->add(function ($field, $table, $message = null) use ($rules) {
            Log::debug($field);
            return $field === 0 || $rules->existsIn(['manufacturer_id'], 'Manufacturers');
        });
        $rules->add($rules->existsIn(['location_id'], 'Locations'));
        $rules->add($rules->existsIn(['created_by_id'], 'CreatedBy', 'The user you created this with no longer exists'));
        $rules->add($rules->existsIn(['modified_by_id'], 'ModifiedBy', 'The user you modified this with no longer exists'));

        return $rules;
    }

    /**
     * @param  Query $query
     * @return Query $equipments
     * Used by: [
     *  Equipments/Add
     * ]
     */
    public function findActiveEquipment(Query $query)
    {
        $user =  Configure::read('GlobalAuth');

        if ($user['active_store'] == 'Dashboard') {
            $equipments = $query->find('list')->where(['store_id in' => array_column($user['stores'], 'id')])->order(['position' => 'ASC']);
        } else {
            $equipments = $query->find('list')->where(['store_id =' => $user['active_store']])->order(['position' => 'ASC']);
        }

        return $equipments;
    }
}
