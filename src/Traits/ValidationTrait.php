<?php

namespace Yoeunes\Larafast\Traits;

trait ValidationTrait
{
    /** @var array */
    protected $rules = [];

    /** @var array */
    protected $messages = [];

    /** @var array */
    protected $fillableAttributes = [];

    /**
     * @param string|null $rule
     *
     * @return array
     */
    public function getRules(string $rule = null): array
    {
        return array_key_exists($rule, $this->rules) ? $this->rules[$rule] : $this->rules;
    }

    /**
     * @param array $rules
     *
     * @return $this
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param array $messages
     *
     * @return $this
     */
    public function setMessages(array $messages)
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * @param string|null $attribute
     *
     * @return array
     */
    public function getFillableAttributes(string $attribute = null): array
    {
        if (array_key_exists($attribute, $this->fillableAttributes)) {
            return $this->fillableAttributes[$attribute];
        }

        return ! empty($this->fillableAttributes) ? $this->fillableAttributes : $this->fillable;
    }

    /**
     * @param array $fillableAttributes
     *
     * @return $this
     */
    public function setFillableAttributes(array $fillableAttributes)
    {
        $this->fillableAttributes = $fillableAttributes;

        return $this;
    }
}
