<?php
declare(strict_types=1);

namespace App\Model\Behavior;

use ArrayObject;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Routing\Router;

/**
 * StoreScope behavior
 */
class StoreScopeBehavior extends Behavior
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
    ];

    public function beforeFind(EventInterface $event, Query $query, ArrayObject $options) {
        if (isset($options['ignoreBeforeFind']) || isset($options['ignoreStoreScope']))  return $query;
        return $query->where([$this->getTable()->getAlias() . '.store_id' => Router::getRequest()->getAttribute('identity')->active_store]);
    }

    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options) {
        $entity->store_id = $entity->store_id?: Router::getRequest()->getAttribute('identity')->active_store;
    }
}
