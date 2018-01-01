<?php

namespace Yoeunes\Larafast\DataTables;

use Yoeunes\Larafast\Entities\Entity;

class DefaultDataTable extends DataTable
{
    /**
     * DefaultDataTable constructor.
     *
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->setEntity($entity);
    }
}
