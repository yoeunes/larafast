<?php

namespace Yoeunes\Larafast\Traits;

use Yoeunes\Larafast\Entities\Entity;
use Yoeunes\Larafast\Policies\DefaultPolicy;
use Yoeunes\Larafast\Policies\Policy;

trait PolicyTrait
{
    /** @var Policy */
    protected $policy;

    /**
     * @return Policy
     */
    private function guessPolicyFromEntityName()
    {
        if ('' !== $entity = $this->entityBaseName()) {
            $policy = config('larafast.policies_namespace').'\\'.$entity.'Policy';
            if (class_exists($policy)) {
                $this->policy = $policy;

                /** @var Policy $default */
                $default = new $policy();

                $default->setEntity($this->getEntity());

                return $default;
            }

            return new DefaultPolicy($this->getEntity());
        }
    }

    /**
     * @return string
     */
    abstract public function entityBaseName(): string;

    /**
     * @return Entity
     */
    abstract public function getEntity(): Entity;

    /**
     * @return Policy
     */
    public function getPolicy()
    {
        if ($this->policy instanceof Policy) {
            return $this->policy;
        }

        if (is_a($this->policy, Policy::class, true)) {
            return new $this->policy();
        }

        return $this->guessPolicyFromEntityName();
    }

    /**
     * @param Policy $policy
     *
     * @return $this
     */
    public function setPolicy(Policy $policy = null)
    {
        $this->policy = $policy;

        return $this;
    }
}
