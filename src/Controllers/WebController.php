<?php

namespace Yoeunes\Larafast\Controllers;

use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Collections\RowCollection;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Yoeunes\Larafast\Entities\Entity;
use Yoeunes\Larafast\Jobs\ImportFromExcelJob;
use Yoeunes\Larafast\Middlewares\UriSessionForWebRoutes;
use Yoeunes\Larafast\Traits\DataTableScopeTrait;
use Yoeunes\Larafast\Traits\DataTableTrait;
use Yoeunes\Larafast\Traits\ViewTrait;

class WebController extends Controller
{
    public const MESSAGES_ERROR               = 'larafast::messages.error';
    public const MESSAGES_SUCCESS_ACTIVATE    = 'larafast::messages.success.activate';
    public const MESSAGES_SUCCESS_DEACTIVATE  = 'larafast::messages.success.deactivate';
    public const MESSAGES_SUCCESS_DESTROY     = 'larafast::messages.success.destroy';
    public const MESSAGES_SUCCESS_EXCEL_STORE = 'larafast::messages.success.excelStore';
    public const MESSAGES_SUCCESS_STORE       = 'larafast::messages.success.store';
    public const MESSAGES_SUCCESS_UPDATE      = 'larafast::messages.success.update';

    use ViewTrait, DataTableTrait, DataTableScopeTrait;

    /**
     * WebController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware(['web', UriSessionForWebRoutes::class]);
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function index()
    {
        $this->allow(__FUNCTION__);

        return $this->getDataTable()->addScope($this->getDataTableScope())->render($this->getView(__FUNCTION__));
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $this->allow(__FUNCTION__);

        return view($this->getView(__FUNCTION__));
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store()
    {
        $this->allow(__FUNCTION__);

        request()->validate($this->getEntity()->getRules(__FUNCTION__), $this->getEntity()->getMessages());

        $entity = $this->getService()->store(request($this->getEntity()->getFillableAttributes(__FUNCTION__)));

        if ($entity instanceof Entity) {
            return request()->wantsJson() ? $this->created(null, fractal($entity, $this->getTransformer())->toArray()['data']) : success(trans(static::MESSAGES_SUCCESS_STORE));
        }

        return request()->wantsJson() ? $this->internalError() : error(trans(static::MESSAGES_ERROR));
    }

    /**
     * @param int $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id)
    {
        $this->allow(__FUNCTION__);

        $entity = $this->getEntity()->findOrFail($id);

        return view($this->getView(__FUNCTION__), compact('entity'));
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(int $id)
    {
        return redirect()->route(preg_replace('/\.show/', '.edit', app('router')->getCurrentRoute()->getName()), $id);
    }

    /**
     * @param int $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(int $id)
    {
        $this->allow(__FUNCTION__);

        /** @var Entity $entity */
        $entity = $this->getEntity()->findOrFail($id);

        request()->validate($this->getEntity()->getRules(__FUNCTION__), $this->getEntity()->getMessages());

        if ($this->getService()->update($entity, request($this->getEntity()->getFillableAttributes(__FUNCTION__)))) {
            return request()->wantsJson() ? $this->accepted() : success(trans(static::MESSAGES_SUCCESS_UPDATE));
        }

        return error(trans(static::MESSAGES_ERROR));
    }

    /**
     * @param int $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $this->allow(__FUNCTION__);

        /** @var Entity $entity */
        $entity = $this->getEntity()->findOrFail($id);

        if ($this->getService()->destroy($entity)) {
            return request()->wantsJson() ? $this->noContent() : success(trans(static::MESSAGES_SUCCESS_DESTROY));
        }

        return request()->wantsJson() ? $this->internalError() : error(trans(static::MESSAGES_ERROR));
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function excelCreate()
    {
        $this->allow(__FUNCTION__);

        return view($this->getView(__FUNCTION__));
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function excelStore()
    {
        $this->allow(__FUNCTION__);

        if (! request()->hasFile('excel')) {
            return back();
        }

        $path = request()->file('excel')->getRealPath();

        /** @var RowCollection $data */
        $data = app('excel')
            ->load($path)
            ->get()
            ->map(function ($value) {
                return collect($this->getEntity()->getExcelAttributes())->map(function ($default, $attribute) use ($value) {
                    return $value->{$attribute} ?? getExcelAttributesDefaultValue($default);
                });
            });

        dispatch(new ImportFromExcelJob($this->entityName(), $data->toArray()));

        return information(trans(static::MESSAGES_SUCCESS_EXCEL_STORE));
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function excelDownload()
    {
        $this->allow(__FUNCTION__);

        $records = $this->getEntity()->get()->toArray();
        $table   = $this->getEntity()->getTable();

        return app('excel')->create($table, function (LaravelExcelWriter $excel) use ($records, $table) {
            $excel->sheet($table, function (LaravelExcelWorksheet $sheet) use ($records) {
                $sheet->fromArray($records);
            });
        })->download();
    }

    /**
     * @param int $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate(int $id)
    {
        $this->allow(__FUNCTION__);

        /** @var Entity $entity */
        $entity = $this->getEntity()->findOrFail($id);

        if ($this->getService()->activate($entity)) {
            return success(trans(static::MESSAGES_SUCCESS_ACTIVATE));
        }

        return error(trans(static::MESSAGES_ERROR));
    }

    /**
     * @param int $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deactivate(int $id)
    {
        $this->allow(__FUNCTION__);

        /** @var Entity $entity */
        $entity = $this->getEntity()->findOrFail($id);

        if ($this->getService()->deactivate($entity)) {
            return success(trans(static::MESSAGES_SUCCESS_DEACTIVATE));
        }

        return error(trans(static::MESSAGES_ERROR));
    }
}
