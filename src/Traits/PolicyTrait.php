<?php

namespace Yoeunes\Larafast\Traits;

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
                return new $policy();
            }
        }
    }

    /**
     * @return string
     */
    abstract public function entityBaseName(): string;

    /**
     * @return Policy
     */
    public function getPolicy()
    {
        if ($this->policy instanceof Policy || is_a($this->policy, Policy::class, true)) {
            return $this->policy;
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
