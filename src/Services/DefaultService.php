<?php

namespace Yoeunes\Larafast\Services;

use Yoeunes\Larafast\Entities\Entity;

class DefaultService extends Service
{
    /**
     * DefaultService constructor.
     *
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->setEntity($entity);
    }
}
