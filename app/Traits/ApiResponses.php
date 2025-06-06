<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\MessageBag;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

trait ApiResponses
{
    /**
     * Standard API error response
     */
    protected function apiErrorResponse(
        string $message,
        int $statusCode,
        mixed $errors = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'status' => $statusCode,
            'timestamp' => now()->toDateTimeString(),
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Convert validation exception to response
     */
    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
        return $this->apiErrorResponse(
            'Validation failed',
            $exception->status,
            $exception->errors()
        );
    }

    /**
     * Handle 500 Internal Server Errors
     */
    protected function internalServerErrorResponse(
        Throwable $exception,
        bool $includeDebugInfo = false
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => 'Internal Server Error',
            'status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
            'timestamp' => now()->toDateTimeString(),
        ];

        if ($includeDebugInfo) {
            $response['error'] = $exception->getMessage();
            $response['file'] = $exception->getFile();
            $response['line'] = $exception->getLine();
            $response['trace'] = config('app.debug') ? $exception->getTrace() : 'Hidden in production';
        }

        return response()->json($response, ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
    }

}
