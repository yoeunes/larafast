<?php

namespace Yoeunes\Larafast\Traits;

use Yoeunes\Larafast\Entities\Entity;
use Yoeunes\Larafast\Jobs\Job;

trait JobTrait
{
    /** @var Job */
    protected $job;

    /**
     * @return Job
     */
    private function guessJobFromEntityName()
    {
        if ('' !== $entity = $this->entityBaseName()) {
            $job = config('larafast.jobs_namespace').'\\'.$entity.'Job';

            if (class_exists($job)) {
                $this->job = $job;

                /** @var Job $default */
                $default = new $job();

                $default->setEntity($this->getEntity());

                return $default;
            }
        }
    }

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        if ($this->job instanceof Job) {
            return $this->job;
        }

        if (is_a($this->job, Job::class, true)) {
            return new $this->job();
        }

        return $this->guessJobFromEntityName();
    }

    /**
     * @param Job $job
     *
     * @return $this
     */
    public function setJob(Job $job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * @return string
     */
    abstract public function entityBaseName(): string;

    /**
     * @return Entity
     */
    abstract public function getEntity();
}
