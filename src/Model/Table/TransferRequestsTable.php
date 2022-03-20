<?php
namespace App\Model\Table;

use App\Model\Entity\TransferRequest;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TransferRequests Model
 *
 * @property StoresTable|BelongsTo $ToStores
 * @property StoresTable|BelongsTo $FromStores
 * @property TransferStatusesTable|BelongsTo $TransferStatuses
 * @property OrderItemsTable|BelongsTo $OrderItems
 * @method TransferRequest get($primaryKey, $options = [])
 * @method TransferRequest newEntity($data = null, array $options = [])
 * @method TransferRequest[] newEntities(array $data, array $options = [])
 * @method TransferRequest|bool save(EntityInterface $entity, $options = [])
 * @method TransferRequest saveOrFail(EntityInterface $entity, $options = [])
 * @method TransferRequest patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method TransferRequest[] patchEntities($entities, array $data, array $options = [])
 * @method TransferRequest findOrCreate($search, callable $callback = null, $options = [])
 * @mixin TimestampBehavior
 */
class TransferRequestsTable extends Table
{
    public const TRANSFER_REQUEST = 1;
    public const TRANSFER_APPROVED_FOR_PICKUP = 2;
    public const TRANSFER_APPROVED_FOR_DELIVERY = 3;
    public const TRANSFER_COMPLETED = 4;
    public const TRANSFER_DENIED = 5;

    /**
     * Initialize method
     *
     * @param  array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('transfer_requests');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ToStores', [
            'foreignKey' => 'to_store_id',
            'joinType' => 'INNER',
            'className' => 'Stores',
        ]);

        $this->belongsTo('FromStores', [
            'foreignKey' => 'from_store_id',
            'joinType' => 'INNER',
            'className' => 'Stores',
        ]);

        $this->belongsTo('OrderItems');

        $this->belongsTo('ApprovedBy', [
            'foreignKey' => 'approved_by_id',
            'joinType' => 'LEFT',
            'className' => 'Users',
        ]);

        $this->addBehavior('WhoDidIt');
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
            ->uuid('created_by_id')
            ->allowEmptyString('created_by_id', 'create');

        $validator
            ->uuid('modified_by_d')
            ->allowEmptyString('modified_by_id', 'create');

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
        $rules->add($rules->existsIn(['to_store_id'], 'ToStores'));
        $rules->add($rules->existsIn(['from_store_id'], 'FromStores'));
        $rules->add($rules->existsIn(['order_item_id'], 'OrderItems'));

        return $rules;
    }

    public function itemCount(int $transfer_status_id, string $user_id, $store_id = null): int
    {
        $q = $this->find()->where(['TransferRequests.transfer_status_id' => $transfer_status_id]);

        if ($store_id) {
            $q->where(['TransferRequests.from_store_id' => $store_id]);
        } else {
            $q->innerJoinWith('FromStores.Users', function (Query $query) use ($user_id) {
                return $query->where(['Users.id' => $user_id]);
            });
        }

        return $q->count();
    }

    /**
     * @param int[] $statusIds
     * @param string $userId
     * @param string|null $storeId
     * @return Query
     */
    public function getTransfersByStatus(array $statusIds, string $userId, string $storeId = null): Query
    {
        $transfers = $this->find()
            ->where(['TransferRequests.transfer_status_id IN' => $statusIds])
            ->contain([
                'OrderItems.Inventories.Items',
                'FromStores',
                'ToStores',
                'ApprovedBy'
            ]);

        if ($storeId) {
            $transfers->where(['TransferRequests.from_store_id' => $storeId]);
        } else {
            $transfers->innerJoinWith('FromStores.Users', function (Query $query) use ($userId) {
                return $query->where(['Users.id' => $userId]);
            });
        }

        return $transfers;
    }
}
