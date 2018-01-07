<?php

namespace Yoeunes\Larafast\Services;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Yoeunes\Larafast\Entities\Entity;
use Yoeunes\Larafast\Traits\EntityTrait;

class Service
{
    use EntityTrait, AuthorizesRequests;

    /**
     * @param array $attributes
     *
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function store(array $attributes = [])
    {
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
     * @return bool
     */
    public function update(Entity $entity, array $attributes = [])
    {
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
     * @throws \Exception
     *
     * @return bool|null
     */
    public function destroy(Entity $entity)
    {
        return $entity->delete();
    }

    /**
     * @param Entity $entity
     *
     * @return bool
     */
    public function activate(Entity $entity)
    {
        return $entity->activate();
    }

    /**
     * @param Entity $entity
     *
     * @return bool
     */
    public function deactivate(Entity $entity)
    {
        return $entity->deactivate();
    }
}
