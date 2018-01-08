<?php

namespace Yoeunes\Larafast\Transformers;

use Yoeunes\Larafast\Entities\Entity;
use League\Fractal\TransformerAbstract as BaseTransformer;

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
