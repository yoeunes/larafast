<?php

namespace Yoeunes\Larafast\Traits;

use Yoeunes\Larafast\Entities\Entity;
use Yoeunes\Larafast\Gates\DefaultGate;
use Yoeunes\Larafast\Gates\Gate;

trait GateTrait
{
    /** @var Gate */
    protected $gate;

    /**
     * @return Gate
     */
    private function guessGateFromEntityName()
    {
        if ('' !== $entity = $this->entityBaseName()) {
            $gate = config('larafast.gates_namespace').'\\'.$entity.'Gate';
            if (class_exists($gate)) {
                $this->gate = $gate;

                /** @var Gate $default */
                $default = new $gate();

                $default->setEntity($this->getEntity());

                return $default;
            }

            return new DefaultGate($this->getEntity());
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
     * @return Gate
     */
    public function getGate()
    {
        if ($this->gate instanceof Gate) {
            return $this->gate;
        }

        if (is_a($this->gate, Gate::class, true)) {
            return new $this->gate();
        }

        return $this->guessGateFromEntityName();
    }

    /**
     * @param Gate $gate
     *
     * @return $this
     */
    public function setGate(Gate $gate = null)
    {
        $this->gate = $gate;

        return $this;
    }
}
