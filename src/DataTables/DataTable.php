<?php

namespace Yoeunes\Larafast\DataTables;

use Illuminate\Support\Facades\Schema;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable as BaseDataTable;
use Yoeunes\Larafast\Entities\Entity;
use Yoeunes\Larafast\Traits\EntityTrait;

class DataTable extends BaseDataTable
{
    use EntityTrait;

    protected $columns = [];

    protected $datatableAction = 'admin.default.action';

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        if($this->getEntity() instanceof HasMedia && $this->getEntity()->showImageInDataTable) {
            $dataTable->addColumn('image', function (Entity $entity) {
                return '<img src="'.asset($entity->image()).'"  width="80" height="80">';
            })
                ->rawColumns(['image', 'action']);
        }

        return $dataTable
            ->addColumn('action', $this->datatableAction);
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

        if($this->getEntity() instanceof HasMedia && $this->getEntity()->showImageInDataTable) {
            $builder
                ->addColumn([
                    'defaultContent' => '',
                    'data'           => 'image',
                    'name'           => 'image',
                    'title'          => 'Image',
                    'data-class'     => 'min-width center'
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
