<?php

namespace Yoeunes\Larafast\Traits;

trait BlacklistTrait
{
    /** @var array $blacklist */
    protected $blacklist = [];

    /**
     * @return array
     */
    public function getBlacklist(): array
    {
        return $this->blacklist;
    }

    /**
     * @param array $blacklist
     *
     * @return $this
     */
    public function setBlacklist(array $blacklist)
    {
        $this->blacklist = $blacklist;

        return $this;
    }
}
