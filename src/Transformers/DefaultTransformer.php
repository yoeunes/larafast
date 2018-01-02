<?php

namespace Yoeunes\Larafast\Services;

use Yoeunes\Larafast\Entities\Entity;

class DefaultTransformer extends Transformer
{
    /**
     * A Fractal transformer.
     *
     * @param Entity $entity
     *
     * @return array
     */
    public function transform(Entity $entity)
    {
        return $entity->toArray();
    }
}
