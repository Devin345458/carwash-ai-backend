<?php

namespace Polymorphic\Model\Behavior;

use Cake\ORM\Behavior;
use Polymorphic\ORM\Association\MorphsMany;
use Polymorphic\ORM\Association\MorphsTo;

class MorphBehavior extends Behavior
{

    /**
     * Creates a new BelongsTo association between this table and a target
     * table. A "belongs to" association is a N-1 relationship where this table
     * is the N side, and where there is a single associated record in the target
     * table for each one in this table.
     *
     * Target table can be inferred by its name, which is provided in the
     * first argument, or you can either pass the to be instantiated or
     * an instance of it directly.
     *
     * The options array accept the following keys:
     *
     * - className: The class name of the target table object
     * - targetTable: An instance of a table object to be used as the target table
     * - foreignKey: The name of the field to use as foreign key, if false none
     *   will be used
     * - conditions: array with a list of conditions to filter the join with
     * - joinType: The type of join to be used (e.g. INNER)
     * - strategy: The loading strategy to use. 'join' and 'select' are supported.
     * - finder: The finder method to use when loading records from this association.
     *   Defaults to 'all'. When the strategy is 'join', only the fields, containments,
     *   and where conditions will be used from the finder.
     *
     * This method will return the association object that was built.
     *
     * @param string $associated the alias for the target table. This is used to
     * uniquely identify the association
     * @param string $morphKey
     * @param array<string, mixed> $options list of options to configure the association definition
     * @return MorphsTo
     */
    public function morphsTo(string $associated, array $options = []): MorphsTo
    {
        $options += ['sourceTable' => $this->_table];

        /** @var MorphsTo $association */
        $association = $this->table()->associations()->load(MorphsTo::class, $associated, $options);

        return $association;
    }


    /**
     * Creates a new HasMany association between this table and a target
     * table. A "has many" association is a 1-N relationship.
     *
     * Target table can be inferred by its name, which is provided in the
     * first argument, or you can either pass the class name to be instantiated or
     * an instance of it directly.
     *
     * The options array accept the following keys:
     *
     * - className: The class name of the target table object
     * - targetTable: An instance of a table object to be used as the target table
     * - foreignKey: The name of the field to use as foreign key, if false none
     *   will be used
     * - dependent: Set to true if you want CakePHP to cascade deletes to the
     *   associated table when an entity is removed on this table. The delete operation
     *   on the associated table will not cascade further. To get recursive cascades enable
     *   `cascadeCallbacks` as well. Set to false if you don't want CakePHP to remove
     *   associated data, or when you are using database constraints.
     * - cascadeCallbacks: Set to true if you want CakePHP to fire callbacks on
     *   cascaded deletes. If false the ORM will use deleteAll() to remove data.
     *   When true records will be loaded and then deleted.
     * - conditions: array with a list of conditions to filter the join with
     * - sort: The order in which results for this association should be returned
     * - saveStrategy: Either 'append' or 'replace'. When 'append' the current records
     *   are appended to any records in the database. When 'replace' associated records
     *   not in the current set will be removed. If the foreign key is a null able column
     *   or if `dependent` is true records will be orphaned.
     * - strategy: The strategy to be used for selecting results Either 'select'
     *   or 'subquery'. If subquery is selected the query used to return results
     *   in the source table will be used as conditions for getting rows in the
     *   target table.
     * - finder: The finder method to use when loading records from this association.
     *   Defaults to 'all'.
     *
     * This method will return the association object that was built.
     *
     * @param string $associated the alias for the target table. This is used to
     * uniquely identify the association
     * @param string $morphKey
     * @param array<string, mixed> $options list of options to configure the association definition
     * @return MorphsMany
     */
    public function morphsMany(string $associated, string $morphKey, array $options = []): MorphsMany
    {
        $options += ['sourceTable' => $this->_table];
        $options += ['morphKey' => $morphKey];

        /** @var MorphsMany $association */
        $association = $this->table()->associations()->load(MorphsMany::class, $associated, $options);

        return $association;
    }
}
