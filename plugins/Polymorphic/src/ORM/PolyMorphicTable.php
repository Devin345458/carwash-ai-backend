<?php

namespace Polymorphic\ORM;

use Cake\ORM\Table;

class PolyMorphicTable extends Table
{

    public function getPrimaryKey(): string
    {
        return 'id';
    }

    /**
     * Alias a field with the table's current alias.
     *
     * If field is already aliased it will result in no-op.
     *
     * @param string $field The field to alias.
     * @return string The field prefixed with the table alias.
     */
    public function aliasField(string $field): string
    {
        if (strpos($field, '.') !== false) {
            return $field;
        }

        return $this->getAlias() . '.' . $field;
    }

    /**
     * Returns the type of the given column. If there is no single use type is configured,
     * the column type will be looked for inside the default mapping. If neither exist,
     * null will be returned.
     *
     * @param string|int $column The type for a given column
     * @return string|null
     */
    public function type($column): ?string
    {
        return 'integer';
    }
}
