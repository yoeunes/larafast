<?php

namespace Yoeunes\Larafast\Traits;

use Yoeunes\Larafast\Entities\Entity;

trait EntityTrait
{
    /** @var Entity */
    protected $entity;

    /**
     * @return Entity
     */
    public function getEntity(): Entity
    {
        if ($this->entity instanceof Entity) {
            return $this->entity;
        }

        if (is_a($this->entity, Entity::class, true)) {
            return new $this->entity();
        }
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    public function setEntity(Entity $entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return string
     */
    public function entityName(): string
    {
        if ($this->entity instanceof Entity) {
            return get_class($this->entity);
        }

        if (is_a($this->entity, Entity::class, true)) {
            return $this->entity;
        }

        return '';
    }

    /**
     * @return string
     */
    public function entityBaseName(): string
    {
        return class_basename($this->entityName());
    }
}
