<?php

namespace Yoeunes\Larafast\Traits;

use Yoeunes\Larafast\Entities\Entity;
use Yoeunes\Larafast\Services\Service;
use Yoeunes\Larafast\Services\DefaultService;

trait ServiceTrait
{
    /** @var Service */
    protected $service;

    /**
     * @return Service|DefaultService
     */
    private function guessServiceFromEntityName()
    {
        if ('' !== $entity = $this->entityBaseName()) {
            $service = config('larafast.namespace.service').'\\'.$entity.'Service';
            if (class_exists($service)) {
                $this->service = $service;

                /** @var Service $default */
                $default = new $service();

                $default->setEntity($this->getEntity());

                return $default;
            }

            return new DefaultService($this->getEntity());
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
     * @return Service
     */
    public function getService(): Service
    {
        if ($this->service instanceof Service) {
            return $this->service;
        }

        if (is_a($this->service, Service::class, true)) {
            return new $this->service();
        }

        return $this->guessServiceFromEntityName();
    }

    /**
     * @param Service $service
     *
     * @return $this
     */
    public function setService(Service $service)
    {
        $this->service = $service;

        return $this;
    }
}
