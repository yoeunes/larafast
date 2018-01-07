<?php

namespace Yoeunes\Larafast\Traits;

trait FilesTrait
{
    /** @var array */
    protected $files = [];

    /**
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param array $files
     *
     * @return $this
     */
    public function setFiles(array $files)
    {
        $this->files = $files;

        return $this;
    }
}
