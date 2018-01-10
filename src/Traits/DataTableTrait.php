<?php

namespace Yoeunes\Larafast\Traits;

use Yoeunes\Larafast\Entities\Entity;
use Yoeunes\Larafast\DataTables\DataTable;
use Yoeunes\Larafast\DataTables\DefaultDataTable;

trait DataTableTrait
{
    /** @var DataTable */
    protected $dataTable;

    /**
     * @return DataTable
     */
    public function getDataTable(): DataTable
    {
        if ($this->dataTable instanceof DataTable) {
            return $this->dataTable;
        }

        if (is_a($this->dataTable, DataTable::class, true)) {
            return new $this->dataTable();
        }

        return $this->guessDataTableFromEntityName();
    }

    /**
     * @param DataTable $dataTable
     *
     * @return $this
     */
    public function setDataTable(DataTable $dataTable)
    {
        $this->dataTable = $dataTable;

        return $this;
    }

    /**
     * @return DataTable|DefaultDataTable
     */
    private function guessDataTableFromEntityName()
    {
        if ('' !== $entity = $this->entityBaseName()) {
            $dataTable = config('larafast.namespace.datatables').'\\'.$entity.'DataTable';

            if (class_exists($dataTable)) {
                $this->dataTable = $dataTable;

                /** @var DataTable $default */
                $default = new $dataTable();

                $default->setEntity($this->getEntity());

                return $default;
            }

            return new DefaultDataTable($this->getEntity());
        }
    }

    /**
     * @return string
     */
    abstract public function entityBaseName(): string;

    /**
     * @return Entity
     */
    abstract public function getEntity(): Entity;
}
