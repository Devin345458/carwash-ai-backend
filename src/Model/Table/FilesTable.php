<?php
namespace App\Model\Table;

use App\Model\Entity\File;
use ArrayObject;
use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Log\Log;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Routing\Router;
use Cake\Validation\Validator;

/**
 * Photos Model
 *
 * @method File get($primaryKey, $options = [])
 * @method File newEntity($data = null, array $options = [])
 * @method File[] newEntities(array $data, array $options = [])
 * @method File|bool save(EntityInterface $entity, $options = [])
 * @method File saveOrFail(EntityInterface $entity, $options = [])
 * @method File patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method File[] patchEntities($entities, array $data, array $options = [])
 * @method File findOrCreate($search, callable $callback = null, $options = [])
 */
class FilesTable extends Table
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

        $this->setTable('files');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('MyUpload', [
            'name' => [],
        ]);

        $this->addBehavior('Timestamp');
        $this->addBehavior('WhoDidIt', ['contain' => false]);

        $this->hasMany('Items');

        $this->belongsToMany('Repairs');

        $this->hasMany('Equipments');
        $this->belongsToMany('Equipments');
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
            ->requirePresence('name', 'create');

        $validator
            ->scalar('dir')
            ->maxLength('dir', 255)
            ->allowEmptyString('dir', 'create');

        $validator
            ->scalar('size')
            ->maxLength('size', 255)
            ->allowEmptyString('size', 'create');

        $validator
            ->scalar('type')
            ->maxLength('type', 255)
            ->allowEmptyString('type', 'create');

        return $validator;
    }

    public function beforeSave(EventInterface $event, EntityInterface $file) {
        $file->company_id = $file->company_id?: Configure::read('COMPANY_ID');
     }


    public function findSearch(Query $query)
    {
        $params = Router::getRequest()->getQueryParams();
        if ($params['search'] && $params['search'] !== null) {
            $search = $params['search'];
            $query->where(
                [
                    'OR' => [
                        'name like' => '%' . $search . '%',
                    ],
                ]
            );
        }

        return $query;
    }
}
