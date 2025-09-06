<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

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
        $statusCode = $exception instanceof HttpExceptionInterface
            ? $exception->getStatusCode()
            : 500;

        return response()->json([
            'success' => false,
            'data' => null,
            'message' => $exception->getMessage(),
            'timestamp' => now()->toISOString()
        ], $statusCode);
    }
}
