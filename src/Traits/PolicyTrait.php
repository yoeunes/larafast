<?php

namespace Yoeunes\Larafast\Traits;

use Yoeunes\Larafast\Policies\Policy;

trait PolicyTrait
{
    /** @var string */
    protected $policy;

    /**
     * @return string
     */
    private function guessPolicyFromEntityName()
    {
        if ('' !== $entity = $this->entityBaseName()) {
            $policy = config('larafast.namespace.policy').'\\'.$entity.'Policy';
            if (class_exists($policy)) {
                $this->policy = $policy;

                return $policy;
            }
        }

        return '';
    }

    /**
     * @return string
     */
    abstract public function entityBaseName(): string;

    /**
     * @return string
     */
    public function getPolicy()
    {
        if ($this->policy instanceof Policy) {
            return get_class($this->policy);
        }

        if (is_a($this->policy, Policy::class, true)) {
            return $this->policy;
        }

        return $this->guessPolicyFromEntityName();
    }

    /**
     * @param string $policy
     *
     * @return $this
     */
    public function setPolicy(string $policy = '')
    {
        $this->policy = $policy;

        return $this;
    }
}
