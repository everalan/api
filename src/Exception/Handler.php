<?php

namespace Everalan\Api\Exception;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends \App\Exceptions\Handler
{
    public function render($request, Exception $exception)
    {
        // Convert Eloquent's 500 ModelNotFoundException into a 404 NotFoundHttpException
        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            $exception = new NotFoundHttpException('Item Not Found.', $exception);
        }

        return new Response($this->prepareReplacements($exception), $this->getStatusCode($exception));
    }

    /**
     * Prepare the replacements array by gathering the keys and values.
     *
     * @param \Exception $exception
     *
     * @return array
     */
    protected function prepareReplacements(Exception $exception)
    {
        $replacements = [
            'message' => $exception->getMessage(),
            'code' => $this->getStatusCode($exception),
        ];

        if (!($exception instanceof HttpException))
        {
            $replacements['message'] = 'Server Error.';
        }

        if ($exception instanceof ValidationException) {
            $replacements['message'] = $exception->errors()[0];
            $replacements['status_code'] = $exception->status;
        }

        if ($code = $exception->getCode()) {
            $replacements['code'] = $code;
        }

        return $replacements;
    }

    /**
     * Get the status code from the exception.
     *
     * @param \Exception $exception
     *
     * @return int
     */
    protected function getStatusCode(Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            return $exception->status;
        }

        return $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500;
    }
}
