<?php

namespace Yoeunes\Larafast\Traits;

use Yoeunes\Larafast\DataTables\Scopes\DataTableScope;
use Yoeunes\Larafast\DataTables\Scopes\DefaultDataTableScope;

trait DataTableScopeTrait
{
    /** @var DataTableScope */
    protected $dataTableScope;

    /**
     * @return DataTableScope|DefaultDataTableScope
     */
    private function guessDataTableScopeFromEntityName()
    {
        if ('' !== $entity = $this->entityBaseName()) {
            $dataTableScope = config('larafast.datatable_scope_namespace').'\\'.$entity.'DataTableScope';
            if (class_exists($dataTableScope)) {
                $this->dataTableScope = $dataTableScope;

                return new $dataTableScope();
            }

            return new DefaultDataTableScope();
        }
    }

    /**
     * @return DataTableScope
     */
    public function getDataTableScope(): DataTableScope
    {
        if ($this->dataTableScope instanceof DataTableScope) {
            return $this->dataTableScope;
        }

        if (is_a($this->dataTableScope, DataTableScope::class, true)) {
            return new $this->dataTableScope();
        }

        return $this->guessDataTableScopeFromEntityName();
    }

    /**
     * @param DataTableScope $dataTableScope
     *
     * @return $this
     */
    public function setDataTableScope(DataTableScope $dataTableScope)
    {
        $this->dataTableScope = $dataTableScope;

        return $this;
    }

    /**
     * @return string
     */
    abstract public function entityBaseName(): string;
}
