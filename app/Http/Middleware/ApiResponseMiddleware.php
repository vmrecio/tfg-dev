<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;

class ApiResponseMiddleware
{
    public function handle(Request $request, Closure $next): JsonResponse
    {
        try {
            $response = $next($request);

            $status = $response->getStatusCode();

            if ($status === 422) {
                return $response;
            }

            if ($status === 204) {
                return $this->formatNoContentSuccess();
            }

            // Treat non-exception 4xx/5xx responses as errors
            if ($status >= 400) {
                return $this->formatHttpErrorResponse($response);
            }

            return $this->formatSuccessResponse($response);
        } catch (Throwable $exception) {
            return $this->formatErrorResponse($exception);
        }
    }

    private function formatSuccessResponse($response): JsonResponse
    {
        if ($response instanceof JsonResponse) {
            $data = $response->getData(true) ?? [];
        } else {
            $decoded = json_decode($response->getContent() ?? '', true);
            $data = is_array($decoded) ? $decoded : [];
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Request completed successfully',
            'timestamp' => now()->toISOString()
        ], $response->getStatusCode());
    }

    private function formatNoContentSuccess(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Request completed successfully',
            'timestamp' => now()->toISOString()
        ], 204);
    }

    private function formatErrorResponse(Throwable $exception): JsonResponse
    {
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        } elseif ($exception instanceof AuthenticationException) {
            $statusCode = 401;
        } elseif ($exception instanceof AuthorizationException) {
            $statusCode = 403;
        } else {
            $statusCode = 500;
        }

        return response()->json([
            'success' => false,
            'data' => null,
            'message' => $exception->getMessage(),
            'timestamp' => now()->toISOString()
        ], $statusCode);
    }

    private function formatHttpErrorResponse($response): JsonResponse
    {
        $status = $response->getStatusCode();

        $message = 'Request failed';
        if ($response instanceof JsonResponse) {
            $payload = $response->getData(true);
            if (is_array($payload) && isset($payload['message'])) {
                $message = (string) $payload['message'];
            }
        } else {
            $decoded = json_decode($response->getContent() ?? '', true);
            if (is_array($decoded) && isset($decoded['message'])) {
                $message = (string) $decoded['message'];
            }
        }

        return response()->json([
            'success' => false,
            'data' => null,
            'message' => $message,
            'timestamp' => now()->toISOString(),
        ], $status);
    }
}
