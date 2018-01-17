<?php

namespace Yoeunes\Larafast\Jobs;

use Illuminate\Support\Collection;

class ImportFromExcelJob extends Job
{
    public $tries = 1;

    public $timeout = 3600;

    /** @var array $data */
    private $data;

    /**
     * ImportFromExcelJob constructor.
     *
     * @param string $entity
     * @param array $data
     */
    public function __construct(string $entity, array $data)
    {
        $this->setEntity($entity);
        $this->data = $data;
    }

    public function handle()
    {
        $this->formatExcelData();

        $this->saveExcelToDatabase();
    }

    public function formatExcelData()
    {
        $casts = $this->getEntity()->getExcelAttributesCasting();

        foreach($this->data as $index => $row) {
            foreach ($row as $key => $value) {

                if(!array_key_exists($key, $casts)) {
                    continue;
                }

                settype($this->data[$index][$key], $casts[$key]);
            }
        }
    }

    public function saveExcelToDatabase()
    {
        collect($this->data)->chunk(100)->each(function (Collection $item) {
            $this->getEntity()->insertOrUpdate($item->toArray());
        });
    }
}
