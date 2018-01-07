<?php

namespace Yoeunes\Larafast\Gates;

use Yoeunes\Larafast\Entities\Entity;

class DefaultGate extends Gate
{
    /**
     * DefaultGate constructor.
     *
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->setEntity($entity);
    }
}
