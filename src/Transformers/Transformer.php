<?php

namespace Yoeunes\Larafast\Transformers;

use League\Fractal\TransformerAbstract as BaseTransformer;
use Yoeunes\Larafast\Entities\Entity;

class Transformer extends BaseTransformer
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
