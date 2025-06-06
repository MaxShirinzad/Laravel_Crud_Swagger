<?php

namespace App\Exceptions;

use App\Traits\ApiResponses;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    use ApiResponses;

    /**
     * Report or log an exception.
     * @throws Throwable
     */
    public function report(Throwable $e): void
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     * @throws Throwable
     */
    public function render($request, Throwable $e): JsonResponse|ResponseAlias
    {
        // Handle API exceptions
        if ($request->expectsJson()) {
            return $this->handleApiException($request, $e);
        }

        // Special handling for 500 errors in web requests
        if ($this->isHttpException($e) &&
            $e->getStatusCode() === 500) {
            return $this->handleWebServerError($e, $request);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions
     */
    protected function handleApiException($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->invalidJson($request, $exception);
        }

        if ($exception instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($exception->getModel()));
            return $this->apiErrorResponse(
                "No {$model} found with the given ID",
                ResponseAlias::HTTP_NOT_FOUND
            );
        }

        if ($exception instanceof AuthenticationException) {
            return $this->apiErrorResponse(
                $exception->getMessage(),
                ResponseAlias::HTTP_UNAUTHORIZED
            );
        }

        if ($exception instanceof AuthorizationException) {
            return $this->apiErrorResponse(
                $exception->getMessage(),
                ResponseAlias::HTTP_FORBIDDEN
            );
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->apiErrorResponse(
                'The specified method for this request is invalid',
                ResponseAlias::HTTP_METHOD_NOT_ALLOWED
            );
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->apiErrorResponse(
                'The specified URL cannot be found',
                ResponseAlias::HTTP_NOT_FOUND
            );
        }

        if ($exception instanceof HttpException) {
            return $this->apiErrorResponse(
                $exception->getMessage(),
                $exception->getStatusCode()
            );
        }

        if ($exception instanceof InternalErrorException) {
            // Catch-all for unhandled exceptions (500 errors)
            return $this->internalServerErrorResponse(
                $exception,
                config('app.debug')
            );
        }

        // Default unexpected error
        return $this->apiErrorResponse(
            config('app.debug')
                ? $exception->getMessage()
                : 'Unexpected server error',
            ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
            config('app.debug') ? [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace()
            ] : null
        );
    }

    protected function handleWebServerError(Throwable $exception, $request)
    {
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        // Custom error page for production
        return response()->view('errors.500', [
            'exception' => $exception
        ], 500);
    }


}
