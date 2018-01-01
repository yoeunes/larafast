<?php

namespace Yoeunes\Larafast\DataTables\Scopes;

use Yajra\DataTables\Contracts\DataTableScope as BaseDataTableScope;

class DataTableScope implements BaseDataTableScope
{
    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     *
     * @return mixed
     */
    public function apply($query)
    {
        // return $query->where('id', 1);
    }
}
