<?php

namespace Yoeunes\Larafast\Controllers;

use Barryvdh\Cors\HandleCors;
use Laravel\Passport\Http\Middleware\CreateFreshApiToken;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Serializer\JsonApiSerializer;
use Yoeunes\Larafast\Entities\Entity;
use Yoeunes\Larafast\Middlewares\ApiExceptionHandler;
use Yoeunes\Larafast\Traits\ResponseTrait;
use Yoeunes\Larafast\Traits\TransformerTrait;

class ApiController extends Controller
{
    use TransformerTrait, ResponseTrait;

    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware(['api', ApiExceptionHandler::class, CreateFreshApiToken::class, HandleCors::class]);
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->allow(__FUNCTION__);

        $paginator = $this->getService()->paginate(
            request('limit', null),
            request('columns', ['*']),
            request('pageName', 'page'),
            request('page', null)
        );

        $collection = $paginator->getCollection();

        if (0 === $collection->count()) {
            return $this->notFound();
        }

        $data = fractal($collection, $this->getTransformer())
            ->serializeWith(new JsonApiSerializer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->toArray();

        return $this->respond($data);
    }

    /**
     * @param int $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $this->allow(__FUNCTION__);

        /** @var Entity $entity */
        $entity = $this->getEntity()->findOrFail($id);

        $data = fractal($entity, $this->getTransformer())->toArray();

        return $this->respond($data);
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $this->allow(__FUNCTION__);

        request()->validate($this->getEntity()->getRules(__FUNCTION__), $this->getEntity()->getMessages());

        $entity = $this->getService()->store(request($this->getEntity()->getFillableAttributes(__FUNCTION__)));

        if ($entity instanceof Entity) {
            $data = fractal($entity, $this->getTransformer())->toArray();

            return $this->created(null, $data['data']);
        }

        return $this->internalError();
    }

    /**
     * @param $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $id)
    {
        $this->allow(__FUNCTION__);

        /** @var Entity $entity */
        $entity = $this->getEntity()->findOrFail($id);

        request()->validate($this->getEntity()->getRules(__FUNCTION__), $this->getEntity()->getMessages());

        if ($this->getService()->update($entity, request($this->getEntity()->getFillableAttributes(__FUNCTION__)))) {
            return $this->accepted();
        }

        return $this->internalError();
    }

    /**
     * @param $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $this->allow(__FUNCTION__);

        /** @var Entity $entity */
        $entity = $this->getEntity()->findOrFail($id);

        if ($this->getService()->destroy($entity)) {
            return $this->noContent();
        }

        return $this->internalError();
    }
}
