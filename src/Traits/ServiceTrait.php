<?php

namespace Yoeunes\Larafast\Traits;

use Yoeunes\Larafast\Entities\Entity;
use Yoeunes\Larafast\Policies\Policy;
use Yoeunes\Larafast\Services\DefaultService;
use Yoeunes\Larafast\Services\Service;

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
            $service = config('larafast.services_namespace').'\\'.$entity.'Service';
            if (class_exists($service)) {
                $this->service = $service;

                /** @var Service $default */
                $default = new $service();

                $default->setEntity($this->getEntity());

                $default->setPolicy($this->getPolicy());

                return $default;
            }

            return new DefaultService($this->getEntity(), $this->getPolicy());
        }
    }

    /**
     * @return string
     */
    abstract public function entityBaseName(): string;

    /**
     * @return Entity
     */
    abstract public function getEntity();

    /**
     * @return Policy
     */
    abstract public function getPolicy();

    /**
     * @return Service
     */
    public function getService(): Service
    {
        if ($this->service instanceof Service) {
            return $this->service;
        }

        if (is_a($this->service, Service::class, true)) {
            /** @var Service $default */
            $default = new $this->service();

            $default->setEntity($this->getEntity());

            $default->setPolicy($this->getPolicy());

            return $default;
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
