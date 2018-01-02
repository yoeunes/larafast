<?php

namespace Yoeunes\Larafast\Traits;

use Yoeunes\Larafast\Entities\Entity;
use Yoeunes\Larafast\Policies\Policy;

trait PolicyTrait
{
    /** @var Policy */
    protected $policy;


    public function policy($policy = null)
    {
        if (null !== $policy) {
            $this->policy = $policy;

            return $this;
        }

        if ($this->policy instanceof Policy) {
            return $this->policy;
        }

        if (is_a($this->policy, Policy::class, true)) {
            return new $this->policy();
        }

        return $this->guessFromEntityName();
    }

    /**
     * @return Policy
     */
    private function guessFromEntityName()
    {
        if ('' !== $entity = $this->entity_base_name()) {
            $policy = config('larafast.policies_namespace').'\\'.$entity.'Policy';
            if (class_exists($policy)) {
                $this->policy = $policy;

                /** @var Policy $default */
                $default = new $policy();

                $default->setEntity($this->getEntity());

                return $default;
            }
        }
    }

    /**
     * @return string
     */
    abstract public function entity_base_name();

    /**
     * @return Entity
     */
    abstract public function getEntity();

    /**
     * @return Policy
     */
    public function getPolicy(): Policy
    {
        return $this->policy;
    }

    /**
     * @param Policy $policy
     *
     * @return $this
     */
    public function setPolicy(Policy $policy)
    {
        $this->policy = $policy;

        return $this;
    }
}
