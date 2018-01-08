<?php

namespace Yoeunes\Larafast\Traits;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait ResponseTrait
{
    protected $statusCode = Response::HTTP_OK;

    /**
     * @param string|array $data
     * @param array        $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->statusCode, $headers);
    }

    /**
     * @param string|null  $message
     * @param string|array $data
     * @param array        $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($message = null, $data = [], array $headers = [])
    {
        $error = [
            'message'     => $message ?? Response::$statusTexts[$this->statusCode] ?? '',
            'status_code' => $this->statusCode,
        ];

        if (! empty($data)) {
            $error['data'] = $data;
        }

        return $this->respond(['error' => $error], $headers);
    }

    /**
     * @param string|null  $message
     * @param string|array $data
     * @param array        $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithMessage($message = null, $data = [], array $headers = [])
    {
        $response = [
            'message'     => $message ?? Response::$statusTexts[$this->statusCode] ?? '',
            'status_code' => $this->statusCode,
        ];

        if (! empty($data)) {
            $response['data'] = $data;
        }

        return $this->respond(['response' => $response], $headers);
    }

    /**
     * @param LengthAwarePaginator $paginator
     * @param array                $data
     * @param array                $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithPagination(LengthAwarePaginator $paginator, array $data = [], array $headers = [])
    {
        $data = array_merge($data, [
            'paginator' => [
                'total_count'  => $paginator->total(),
                'total_pages'  => ceil($paginator->total() / $paginator->perPage()),
                'current_page' => $paginator->currentPage(),
                'limit'        => $paginator->perPage(),
            ],
        ]);

        return $this->respond($data, $headers);
    }

    /**
     * @param string|null  $message
     * @param string|array $data
     * @param array        $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function notFound($message = null, $data = [], array $headers = [])
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)->respondWithError($message, $data, $headers);
    }

    /**
     * @param string|null  $message
     * @param string|array $data
     * @param array        $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function invalidRequest($message = null, $data = [], array $headers = [])
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($message, $data, $headers);
    }

    /**
     * @param string|null  $message
     * @param string|array $data
     * @param array        $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function internalError($message = null, $data = [], array $headers = [])
    {
        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message, $data, $headers);
    }

    /**
     * @param string|null  $message
     * @param string|array $data
     * @param array        $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function created($message = null, $data = [], array $headers = [])
    {
        return $this->setStatusCode(Response::HTTP_CREATED)->respondWithMessage($message, $data, $headers);
    }

    /**
     * @param string|null  $message
     * @param string|array $data
     * @param array        $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function accepted($message = null, $data = [], array $headers = [])
    {
        return $this->setStatusCode(Response::HTTP_ACCEPTED)->respondWithMessage($message, $data, $headers);
    }

    /**
     * @param string|null  $message
     * @param string|array $data
     * @param array        $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function noContent($message = null, $data = [], array $headers = [])
    {
        return $this->setStatusCode(Response::HTTP_NO_CONTENT)->respondWithMessage($message, $data, $headers);
    }

    /**
     * @param Exception $exception
     *
     * @return Exception
     */
    public function exception(Exception $exception)
    {
        $exception_class = get_class($exception);

        if (array_key_exists($exception_class, config('larafast.exceptions'))) {
            $method  = config('larafast.exceptions')[$exception_class]['method'];
            $message = config('larafast.exceptions')[$exception_class]['message'];

            return $this->{$method}($message);
        }
    }

    /**
     * @param string|null  $message
     * @param string|array $data
     * @param array        $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthorized($message = null, $data = [], array $headers = [])
    {
        return $this->setStatusCode(Response::HTTP_UNAUTHORIZED)->respondWithError($message, $data, $headers);
    }

    /**
     * Create a file download response. Wrapper for Response::download().
     *
     * @param \SplFileInfo|string $file
     * @param string              $name
     * @param array               $headers
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($file, $name = null, array $headers = [])
    {
        $response = new BinaryFileResponse($file, 200, $headers, true, 'attachment');

        if ($name !== null) {
            return $response->setContentDisposition('attachment', $name, Str::ascii($name));
        }

        return $response;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     *
     * @return $this
     */
    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Create a streamed response. Wrapper for Response::stream().
     *
     * @param callable $callback
     * @param int      $status
     * @param array    $headers
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function stream(callable $callback, $status = 200, array $headers = [])
    {
        return new StreamedResponse($callback, $status, $headers);
    }
}
