<?php

namespace Yoeunes\Larafast\Services;

use Yoeunes\Larafast\Entities\Entity;
use Yoeunes\Larafast\Policies\Policy;

class DefaultService extends Service
{
    /**
     * DefaultService constructor.
     *
     * @param Entity $entity
     * @param Policy   $policy
     */
    public function __construct(Entity $entity, Policy $policy = null)
    {
        $this->setEntity($entity);
        $this->setPolicy($policy);
    }
}
