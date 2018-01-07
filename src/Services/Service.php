<?php

namespace Yoeunes\Larafast\Services;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Yoeunes\Larafast\Entities\Entity;
use Yoeunes\Larafast\Policies\Policy;
use Yoeunes\Larafast\Traits\EntityTrait;
use Yoeunes\Larafast\Traits\PolicyTrait;

class Service
{
    use EntityTrait, PolicyTrait, AuthorizesRequests;

    /**
     * @param array $attributes
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function store(array $attributes = [])
    {
        if (is_a($this->getPolicy(), Policy::class, true)) {
            $this->authorize(__FUNCTION__, $this->entityName());
        }

        $entity = $this->getEntity()->create(array_diff_key($attributes, array_flip($this->getEntity()->getFiles())));

        if (!$this->getEntity() instanceof HasMedia) {
            return $entity;
        }

        if (!empty($files = array_intersect($this->getEntity()->getFiles(), array_keys(request()->allFiles())))) {
            $entity
                ->addMultipleMediaFromRequest($this->getEntity()->getFiles())
                ->each(function ($fileAdder) {
                    $fileAdder
                        ->preservingOriginal()
                        ->toMediaCollection();
                });
        }

        return $entity;
    }

    /**
     * @param array  $attributes
     * @param Entity $entity
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return bool
     */
    public function update(Entity $entity, array $attributes = [])
    {
        if (is_a($this->getPolicy(), Policy::class, true)) {
            $this->authorize(__FUNCTION__, $this->entityName());
        }

        $updated = $entity->update(array_diff_key($attributes, array_flip($this->getEntity()->getFiles())));

        if (!$entity instanceof HasMedia) {
            return $updated;
        }

        if (!empty($files = array_intersect($this->getEntity()->getFiles(), array_keys(request()->allFiles())))) {
            if ($entity->clearMediaCollection) {
                $entity->clearMediaCollection();
            }
            $entity
                ->addMultipleMediaFromRequest($this->getEntity()->getFiles())
                ->each(function ($fileAdder) {
                    $fileAdder
                        ->preservingOriginal()
                        ->toMediaCollection();
                });
        }

        return $updated;
    }

    /**
     * @param Entity $entity
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     *
     * @return bool|null
     */
    public function destroy(Entity $entity)
    {
        if (is_a($this->getPolicy(), Policy::class, true)) {
            $this->authorize(__FUNCTION__, $this->entityName());
        }

        return $entity->delete();
    }

    /**
     * @param Entity $entity
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return bool
     */
    public function activate(Entity $entity)
    {
        if (is_a($this->getPolicy(), Policy::class, true)) {
            $this->authorize(__FUNCTION__, $this->entityName());
        }

        return $entity->activate();
    }

    /**
     * @param Entity $entity
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return bool
     */
    public function deactivate(Entity $entity)
    {
        if (is_a($this->getPolicy(), Policy::class, true)) {
            $this->authorize(__FUNCTION__, $this->entityName());
        }

        return $entity->deactivate();
    }
}
