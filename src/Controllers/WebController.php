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
use Yoeunes\Larafast\Traits\ServiceTrait;
use Yoeunes\Larafast\Traits\ViewTrait;

class WebController extends Controller
{
    use ViewTrait, DataTableTrait, DataTableScopeTrait, ServiceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->middleware(['web', UriSessionForWebRoutes::class]);
    }

    public function getPermission(string $method)
    {
        return array_key_exists($method, $map = $this->getAbilityMap()) ? $map[$method] : $method;
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function index()
    {
        $this->authorize($this->getPermission(__FUNCTION__), $this->entityName());

        return $this->getDataTable()->addScope($this->getDataTableScope())->render($this->getView(__FUNCTION__));
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $this->authorize($this->getPermission(__FUNCTION__), $this->entityName());

        return view($this->getView(__FUNCTION__));
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store()
    {
        $this->authorize($this->getPermission(__FUNCTION__), $this->entityName());

        request()->validate($this->getEntity()->getRules(__FUNCTION__), $this->getEntity()->getMessages());

        $entity = $this->getService()->store(request($this->getEntity()->getFillableAttributes(__FUNCTION__)));

        if ($entity instanceof Entity) {
            return success('L\'Entité a été ajoutée avec succès');
        }

        return error('Une erreur s\'est produite veuillez réessayer ultérieurement');
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
        $this->authorize($this->getPermission(__FUNCTION__), $this->getEntity());

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
        $this->authorize($this->getPermission(__FUNCTION__), $this->getEntity());

        /** @var Entity $entity */
        $entity = $this->getEntity()->findOrFail($id);

        request()->validate($this->getEntity()->getRules(__FUNCTION__), $this->getEntity()->getMessages());

        if ($this->getService()->update($entity, request($this->getEntity()->getFillableAttributes(__FUNCTION__)))) {
            return success('L\'Entité a été modifiée avec succès');
        }

        return error('Une erreur s\'est produite veuillez réessayer ultérieurement');
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
        $this->authorize($this->getPermission(__FUNCTION__), $this->getEntity());

        /** @var Entity $entity */
        $entity = $this->getEntity()->findOrFail($id);

        if ($this->getService()->destroy($entity)) {
            return success('L\'Entité a été supprimée avec succès');
        }

        return error('Une erreur s\'est produite veuillez réessayer ultérieurement');
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function excelCreate()
    {
        $this->authorize($this->getPermission(__FUNCTION__), $this->entityName());

        return view($this->getView(__FUNCTION__));
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function excelStore()
    {
        $this->authorize($this->getPermission(__FUNCTION__), $this->entityName());

        if (!request()->hasFile('excel')) {
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

        return information('L\'importation est en cours veuillez patienter');
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function excelDownload()
    {
        $this->authorize($this->getPermission(__FUNCTION__), $this->entityName());

        $records = $this->getEntity()->get()->toArray();
        $table = $this->getEntity()->getTable();

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
        $this->authorize($this->getPermission(__FUNCTION__), $this->getEntity());

        /** @var Entity $entity */
        $entity = $this->getEntity()->findOrFail($id);

        if ($this->getService()->activate($entity)) {
            return success('L\'Entité a été activée avec succès');
        }

        return error('Une erreur s\'est produite veuillez réessayer ultérieurement');
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
        $this->authorize($this->getPermission(__FUNCTION__), $this->getEntity());

        /** @var Entity $entity */
        $entity = $this->getEntity()->findOrFail($id);

        if ($this->getService()->deactivate($entity)) {
            return success('L"Entité a été désactivée avec succès');
        }

        return error('Une erreur s\'est produite veuillez réessayer ultérieurement');
    }
}
