<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.4.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Polymorphic\ORM\Association\Loader;

use Cake\Database\Expression\TupleComparison;
use Cake\ORM\Association;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Closure;
use InvalidArgumentException;
use Polymorphic\ORM\Association\MorphsTo;

/**
 * Implements the logic for loading an association using a SELECT query
 *
 * @internal
 */
class MorphLoader
{
    use LocatorAwareTrait;

    /**
     * The alias of the association loading the results
     *
     * @var string
     */
    protected $alias;

    /**
     * The alias of the source association
     *
     * @var string
     */
    protected $sourceAlias;

    /**
     * The foreignKey to the target association
     *
     * @var array|string
     */
    protected $foreignKey;

    /**
     * The strategy to use for loading, either select or subquery
     *
     * @var string
     */
    protected $strategy;

    /**
     * The binding keys for the source association.
     *
     * @var string
     */
    protected $bindingKeys;

    /**
     * A callable that will return a query object used for loading the association results
     *
     * @var callable
     */
    protected $finder;

    /**
     * The type of the association triggering the load
     *
     * @var string
     */
    protected $associationType;

    /**
     * The sorting options for loading the association
     *
     * @var string
     */
    protected $sort;

    /**
     * Copies the options array to properties in this class. The keys in the array correspond
     * to properties in this class.
     *
     * @param array<string, mixed> $options Properties to be copied to this class
     */
    public function __construct(array $options)
    {
        $this->sourceAlias = $options['sourceAlias'];
        $this->strategy = $options['strategy'];
        $this->bindingKeys = $options['bindingKeys'];
        $this->finder = $options['finder'];
        $this->associationType = $options['associationType'];
        $this->sort = $options['sort'] ?? null;
    }

    /**
     * Returns a callable that can be used for injecting association results into a given
     * iterator. The options accepted by this method are the same as `Association::eagerLoader()`
     *
     * @param array<string, mixed> $options Same options as `Association::eagerLoader()`
     * @return Closure
     */
    public function buildEagerLoader(array $options): Closure
    {
        $options += $this->_defaultOptions();
        $resultMap = $this->_buildResultMap( $options);

        return $this->_resultInjector( $resultMap, $options);
    }

    /**
     * Returns the default options to use for the eagerLoader
     *
     * @return array
     */
    protected function _defaultOptions(): array
    {
        return [
            'foreignKey' => $this->foreignKey,
            'conditions' => [],
            'strategy' => $this->strategy,
            'nestKey' => $this->alias,
            'sort' => $this->sort,
        ];
    }

    /**
     * Auxiliary function to construct a new Query object to return all the records
     * in the target table that are associated to those specified in $options from
     * the source table
     *
     * @param  array  $keys
     * @param  array<string, mixed>  $options  options accepted by eagerLoader()
     * @return Query
     */
    protected function _buildQuery(array $keys, array $options): Query
    {
        $table = $this->getTableLocator()->get($keys[0]);
        $key = $table->getAlias() . '.' . $table->getPrimaryKey();
        $filter = $keys[1];
        $options['fields'] = $options['fields'] ?? [];

        $query = $table->find();
        if (isset($options['finder'])) {
            [$finderName, $opts] = $this->_extractFinder($options['finder']);
            $query = $query->find($finderName, $opts);
        }

        $fetchQuery = $query
            ->select($options['fields'])
            ->where($options['conditions'])
            ->eagerLoaded(true)
            ->enableHydration($options['query']->isHydrationEnabled());
        if ($options['query']->isResultsCastingEnabled()) {
            $fetchQuery->enableResultsCasting();
        } else {
            $fetchQuery->disableResultsCasting();
        }

        $fetchQuery = $this->_addFilteringCondition($fetchQuery, $key, $filter);

        if (!empty($options['sort'])) {
            $fetchQuery->order($options['sort']);
        }

        if (!empty($options['contain'])) {
            $fetchQuery->contain($options['contain']);
        }

        if (!empty($options['queryBuilder'])) {
            $fetchQuery = $options['queryBuilder']($fetchQuery);
        }

        $this->_assertFieldsPresent($fetchQuery, (array)$key);

        return $fetchQuery;
    }

    /**
     * Helper method to infer the requested finder and its options.
     *
     * Returns the inferred options from the finder $type.
     *
     * ### Examples:
     *
     * The following will call the finder 'translations' with the value of the finder as its options:
     * $query->contain(['Comments' => ['finder' => ['translations']]]);
     * $query->contain(['Comments' => ['finder' => ['translations' => []]]]);
     * $query->contain(['Comments' => ['finder' => ['translations' => ['locales' => ['en_US']]]]]);
     *
     * @param array|string $finderData The finder name or an array having the name as key
     * and options as value.
     * @return array
     */
    protected function _extractFinder($finderData): array
    {
        $finderData = (array)$finderData;

        if (is_numeric(key($finderData))) {
            return [current($finderData), []];
        }

        return [key($finderData), current($finderData)];
    }

    /**
     * Checks that the fetching query either has auto fields on or
     * has the foreignKey fields selected.
     * If the required fields are missing, throws an exception.
     *
     * @param  Query  $fetchQuery The association fetching query
     * @param array<string> $key The foreign key fields to check
     * @return void
     * @throws InvalidArgumentException
     */
    protected function _assertFieldsPresent(Query $fetchQuery, array $key): void
    {
        $select = $fetchQuery->aliasFields($fetchQuery->clause('select'));
        if (empty($select)) {
            return;
        }
        $missingKey = function ($fieldList, $key) {
            foreach ($key as $keyField) {
                if (!in_array($keyField, $fieldList, true)) {
                    return true;
                }
            }

            return false;
        };

        $missingFields = $missingKey($select, $key);
        if ($missingFields) {
            $driver = $fetchQuery->getConnection()->getDriver();
            $quoted = array_map([$driver, 'quoteIdentifier'], $key);
            $missingFields = $missingKey($select, $quoted);
        }

        if ($missingFields) {
            throw new InvalidArgumentException(
                sprintf(
                    'You are required to select the "%s" field(s)',
                    implode(', ', $key)
                )
            );
        }
    }

    /**
     * Appends any conditions required to load the relevant set of records in the
     * target table query given a filter key and some filtering values.
     *
     * @param  Query  $query Target table's query
     * @param array<string>|string $key The fields that should be used for filtering
     * @param mixed $filter The value that should be used to match for $key
     * @return Query
     */
    protected function _addFilteringCondition(Query $query, $key, $filter): Query
    {
        if (is_array($key)) {
            $conditions = $this->_createTupleCondition($query, $key, $filter, 'IN');
        } else {
            $conditions = [$key . ' IN' => $filter];
        }

        return $query->andWhere($conditions);
    }

    /**
     * Returns a TupleComparison object that can be used for matching all the fields
     * from $keys with the tuple values in $filter using the provided operator.
     *
     * @param  Query  $query Target table's query
     * @param array<string> $keys the fields that should be used for filtering
     * @param mixed $filter the value that should be used to match for $key
     * @param string $operator The operator for comparing the tuples
     * @return TupleComparison
     */
    protected function _createTupleCondition(Query $query, array $keys, $filter, $operator): TupleComparison
    {
        $types = [];
        $defaults = $query->getDefaultTypes();
        foreach ($keys as $k) {
            if (isset($defaults[$k])) {
                $types[] = $defaults[$k];
            }
        }

        return new TupleComparison($keys, $filter, $types, $operator);
    }

    /**
     * Builds an array containing the results from fetchQuery indexed by
     * the foreignKey value corresponding to this association.
     *
     * @param  Query  $fetchQuery The query to get results from
     * @param array<string, mixed> $options The options passed to the eager loader
     * @return array<string, mixed>
     */
    protected function _buildResultMap(array $options): array
    {
        $resultMap = [];
        $singleResult = in_array($this->associationType, [MorphsTo::MORPH_TO_ONE], true);

        foreach ($options['keys'] as $keys) {
            $fetchQuery = $this->_buildQuery($keys, $options);
            if ($singleResult) {
                $resultMap[implode(';', $keys)] = $fetchQuery->first();
            } else {
                $resultMap[implode(';', $keys)][] = $fetchQuery->first();
            }
        }

        return $resultMap;
    }

    /**
     * Returns a callable to be used for each row in a query result set
     * for injecting the eager loaded rows
     *
     * @param  Query  $fetchQuery the Query used to fetch results
     * @param array<string, mixed> $resultMap an array with the foreignKey as keys and
     * the corresponding target table results as value.
     * @param array<string, mixed> $options The options passed to the eagerLoader method
     * @return Closure
     */
    protected function _resultInjector(array $resultMap, array $options): Closure
    {
        $keys = $this->associationType === Association::MANY_TO_ONE ?
            $this->foreignKey :
            $this->bindingKeys;

        $sourceKeys = [];
        foreach ($keys as $key) {
            $sourceKeys[] = $this->sourceAlias . '__' . $key;
        }

        $nestKey = $options['nestKey'];

        return function ($row) use ($resultMap, $sourceKeys, $nestKey) {
            $values = [];
            foreach ($sourceKeys as $key) {
                $values[] = $row[$key];
            }

            $key = implode(';', $values);
            if (isset($resultMap[$key])) {
                $row[$nestKey] = $resultMap[$key];
            }

            return $row;
        };
    }

}
