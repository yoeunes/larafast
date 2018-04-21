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

    protected $formattedColumns = [];

    protected $actionViewPath;

    public function __construct()
    {
        $this->actionViewPath = config('larafast.path.datatables_default_action');
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     *
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $rowColumns = ['action'];
        $dataTable  = new EloquentDataTable($query);

        if ($this->getEntity() instanceof HasMedia && $this->getEntity()->showImageInDataTable) {
            $dataTable->addColumn('image', function (Entity $entity) {
                return '<img src="'.asset($entity->image($this->getEntity()->imageToShowInDataTable)).'"  width="80" height="80" style="max-width: none;max-height:none;display:inline-block;">';
            });
            $rowColumns[] = 'image';
        }

        foreach ($this->formattedColumns as $index => $column) {
            if (null !== $column) {
                $dataTable->editColumn($index, $column);
                $rowColumns[] = $index;
            }
        }

        return $dataTable
            ->addColumn('action', $this->actionViewPath)
            ->rawColumns($rowColumns);
    }

    public function formatColumns(array $columns = [])
    {
        $result = [];

        foreach ($columns as $index => $column) {
            if (is_int($index)) {
                $result[$column] = null;
                continue;
            }

            $result[$index] = $column;
        }

        return $result;
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
        $query = $this->getEntity()->newQuery()->select($this->getColumns());

        if (count($relations = $this->getEntity()->dataTableEager)) {
            $query->with($relations)->select($this->getEntity()->getTable() . '.*');
        }

        return $query;
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

        if(!empty($parameters = $this->getEntity()->dataTableCustomConfiguration)) {
            $builder->parameters($parameters);
        }

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

        $builder->addAction(['width' => '80px']);

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

        if (count($columns = $this->getEntity()->dataTableColumns)) {
            $this->formattedColumns = $this->formatColumns($columns);

            return array_keys($this->formattedColumns);
        }

        return array_diff(Schema::getColumnListing($this->getEntity()->getTable()), $this->getEntity()->getHidden());
    }

    protected function setColumns(array $columns = [])
    {
        $this->columns = $columns;

        return $this;
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

    /**
     * @param string $actionViewPath
     *
     * @return DataTable
     */
    public function setActionViewPath($actionViewPath)
    {
        $this->actionViewPath = $actionViewPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getActionViewPath()
    {
        return $this->actionViewPath;
    }
}
