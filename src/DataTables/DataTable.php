<?php

namespace Yoeunes\Larafast\DataTables;

use Yoeunes\Larafast\Entities\Entity;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\EloquentDataTable;
use Yoeunes\Larafast\Traits\EntityTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Yajra\DataTables\Services\DataTable as BaseDataTable;

class DataTable extends BaseDataTable
{
    use EntityTrait;

    protected $columns = [];

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     *
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        if ($this->getEntity() instanceof HasMedia && $this->getEntity()->showImageInDataTable) {
            $dataTable->addColumn('image', function (Entity $entity) {
                return '<img src="'.asset($entity->image()).'"  width="80" height="80">';
            })
                ->rawColumns(['image', 'action']);
        }

        return $dataTable
            ->addColumn('action', config('larafast.path.datatables_default_action'));
    }

    /**
     * Get query source of dataTable.
     *
     * @param Entity $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Entity $model)
    {
        return $this->getEntity()->newQuery()->select($this->getColumns());
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $builder = $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax();

        if ($this->getEntity() instanceof HasMedia && $this->getEntity()->showImageInDataTable) {
            $builder
                ->addColumn([
                    'defaultContent' => '',
                    'data'           => 'image',
                    'name'           => 'image',
                    'title'          => 'Image',
                    'data-class'     => 'min-width center',
                ]);
        }

        $builder
            ->addAction(['width' => '80px'])
            ->parameters([
                'dom'     => 'Bfrtip',
                'order'   => [[0, 'desc']],
                'buttons' => [
                    'create',
                    'export',
                    'print',
                    'reset',
                    'reload',
                ],
            ]);

        return $builder;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        if (count($this->columns)) {
            return $this->columns;
        }

        if(count($columns = $this->getEntity()->dataTableColumns)) {
            return $columns;
        }

        return array_diff(Schema::getColumnListing($this->getEntity()->getTable()), $this->getEntity()->getHidden());
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return str_plural(strtolower($this->entityBaseName())).'datatable_'.time();
    }
}
