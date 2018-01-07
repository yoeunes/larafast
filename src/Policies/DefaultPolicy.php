<?php

namespace Yoeunes\Larafast\Policies;

use Yoeunes\Larafast\Entities\Entity;

class DefaultPolicy extends Policy
{
    /**
     * DefaultPolicy constructor.
     *
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->setEntity($entity);
    }
}
