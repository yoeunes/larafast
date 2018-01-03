<?php

namespace Yoeunes\Larafast\Jobs;

use Illuminate\Support\Collection;

class ImportFromExcelJob extends Job
{
    public $tries = 1;

    public $timeout = 3600;

    /** @var array $data */
    private $data;

    public function __construct($entity, array $data)
    {
        $this->setEntity($entity);
        $this->data = $data;
    }

    public function handle()
    {
        collect($this->data)->chunk(100)->each(function(Collection $item) {
            $this->getEntity()->insertOrUpdate($item->toArray());
        });
    }
}
