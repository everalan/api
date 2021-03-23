<?php namespace Everalan\Api\Http;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Response as HttpResponse;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Serializer\ArraySerializer;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Response
{
    protected $manager;
    public function __construct()
    {
        $this->manager = new Manager();
        if($class = config('everalanapi.serializer')) {
            $this->manager->setSerializer(new $class);
        }else{
            $this->manager->setSerializer(new ArraySerializer());
        }
    }

    /**
     * 通用成功响应
     * @return HttpResponse
     */
    public function success()
    {
        $response = new HttpResponse();
        $response->setStatusCode(200);

        return $response;
    }
    /**
     * Respond with a created response and associate a location if provided.
     *
     * @param null|string $location
     *
     * @return \Everalan\Api\Http\Response
     */
    public function created($location = null, $content = null)
    {
        $response = new HttpResponse($content);
        $response->setStatusCode(201);

        if (!is_null($location)) {
            $response->header('Location', $location);
        }

        return $response;
    }

    /**
     * Respond with an accepted response and associate a location and/or content if provided.
     *
     * @param null|string $location
     * @param mixed $content
     *
     * @return \Everalan\Api\Http\Response
     */
    public function accepted($location = null, $content = null)
    {
        $response = new HttpResponse($content);
        $response->setStatusCode(202);

        if (!is_null($location)) {
            $response->header('Location', $location);
        }

        return $response;
    }

    /**
     * Respond with a no content response.
     *
     * @return \Everalan\Api\Http\Response
     */
    public function noContent()
    {
        $response = new HttpResponse(null);

        return $response->setStatusCode(204);
    }

    /**
     * Bind a collection to a transformer and start building a response.
     *
     * @param \Illuminate\Support\Collection $collection
     * @param string|callable|object $transformer
     *
     * @return \Everalan\Api\Http\Response
     */
    public function collection(Collection $collection, $transformer)
    {
        return new HttpResponse($this->manager->createData(new \League\Fractal\Resource\Collection($collection, $transformer))->toArray(), 200);
    }

    /**
     * Bind an item to a transformer and start building a response.
     *
     * @param object $item
     * @param string|callable|object $transformer
     * @param array $parameters
     * @param \Closure $after
     *
     * @return \Everalan\Api\Http\Response
     */
    public function item($item, $transformer)
    {
        return new HttpResponse($this->manager->createData(new \League\Fractal\Resource\Item($item, $transformer))->toArray(), 200);
    }

    public function array($arr)
    {
        return new HttpResponse($arr);
    }

    /**
     * Bind a paginator to a transformer and start building a response.
     *
     * @param \Illuminate\Contracts\Pagination\Paginator $paginator
     * @param string|callable|object $transformer
     * @param array $parameters
     * @param \Closure $after
     *
     * @return \Everalan\Api\Http\Response
     */
    public function paginator(Paginator $paginator, $transformer)
    {
        $books = $paginator->getCollection();

        $resource = new \League\Fractal\Resource\Collection($books, $transformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return new HttpResponse($this->manager->createData($resource)->toArray(), 200);
    }

    /**
     * Return an error response.
     *
     * @param string $message
     * @param int $statusCode
     *
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     */
    public function error($message, $statusCode)
    {
        throw new HttpException($statusCode, $message);
    }

    /**
     * Return a 404 not found error.
     *
     * @param string $message
     *
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     */
    public function errorNotFound($message = 'Not Found')
    {
        $this->error($message, 404);
    }

    /**
     * Return a 400 bad request error.
     *
     * @param string $message
     *
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     */
    public function errorBadRequest($message = 'Bad Request')
    {
        $this->error($message, 400);
    }

    /**
     * Return a 403 forbidden error.
     *
     * @param string $message
     *
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     */
    public function errorForbidden($message = 'Forbidden')
    {
        $this->error($message, 403);
    }

    /**
     * Return a 500 internal server error.
     *
     * @param string $message
     *
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     */
    public function errorInternal($message = 'Internal Error')
    {
        $this->error($message, 500);
    }

    /**
     * Return a 401 unauthorized error.
     *
     * @param string $message
     *
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     */
    public function errorUnauthorized($message = 'Unauthorized')
    {
        $this->error($message, 401);
    }

    /**
     * Return a 405 method not allowed error.
     *
     * @param string $message
     *
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     */
    public function errorMethodNotAllowed($message = 'Method Not Allowed')
    {
        $this->error($message, 405);
    }

    /**
     * Parse Include String.
     *
     * @param array|string $includes Array or csv string of resources to include
     *
     * @return $this
     */
    public function include($includeStr)
    {
        $this->manager->parseIncludes($includeStr);
        return $this;
    }
}

