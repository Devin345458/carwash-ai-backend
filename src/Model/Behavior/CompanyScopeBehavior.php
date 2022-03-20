<?php
declare(strict_types=1);

namespace App\Model\Behavior;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Routing\Router;

/**
 * CompanyScope behavior
 */
class CompanyScopeBehavior extends Behavior
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function beforeFind(EventInterface $event, Query $query, ArrayObject $options) {
        if (isset($options['ignoreBeforeFind']) || isset($options['ignoreCompanyScope'])) return $query;
        return $query->where([$this->getTable()->getAlias() . '.company_id' => Router::getRequest()->getAttribute('identity')->company_id]);
    }

    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options) {
        $entity->company_id = $entity->company_id?: Router::getRequest()->getAttribute('identity')->company_id;
    }
}
