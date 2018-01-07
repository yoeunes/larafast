<?php

namespace Yoeunes\Larafast\Services;

use Yoeunes\Larafast\Entities\Entity;
use Yoeunes\Larafast\Gates\Gate;

class DefaultService extends Service
{
    /**
     * DefaultService constructor.
     *
     * @param Entity $entity
     * @param Gate $gate
     */
    public function __construct(Entity $entity, Gate $gate = null)
    {
        $this->setEntity($entity);
        $this->setGate($gate);
    }
}
