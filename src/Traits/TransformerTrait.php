<?php

namespace Yoeunes\Larafast\Traits;

use Yoeunes\Larafast\Entities\Entity;
use League\Fractal\TransformerAbstract as Transformer;

trait TransformerTrait
{
    /** @var Transformer */
    protected $transformer;

    /**
     * @return Transformer
     */
    private function guessTransformerFromEntityName()
    {
        if ('' !== $entity = $this->entityBaseName()) {
            $transformer = config('larafast.namespace.transformer').'\\'.$entity.'Transformer';
            if (class_exists($transformer)) {
                $this->transformer = $transformer;

                return new $transformer();
            }
        }

        return new \Yoeunes\Larafast\Transformers\Transformer();
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
     * @return Transformer
     */
    public function getTransformer(): Transformer
    {
        if ($this->transformer instanceof Transformer) {
            return $this->transformer;
        }

        if (is_a($this->transformer, Transformer::class, true)) {
            return new $this->transformer();
        }

        return $this->guessTransformerFromEntityName();
    }

    /**
     * @param Transformer $transformer
     *
     * @return $this
     */
    public function setTransformer(Transformer $transformer)
    {
        $this->transformer = $transformer;

        return $this;
    }
}
