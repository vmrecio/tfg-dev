<?php

namespace Tests\Feature\Middleware;

use App\Http\Middleware\ApiResponseMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Exception;

class ApiResponseMiddlewareTest extends TestCase
{
    private ApiResponseMiddleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new ApiResponseMiddleware();
    }

    #[Test]
    public function it_formats_successful_response_correctly(): void
    {
        // Arrange
        $request = Request::create('/test', 'GET');
        $originalData = ['id' => 1, 'name' => 'Test Wedding'];
        $next = function () use ($originalData) {
            return response()->json($originalData, 200);
        };

        // Act
        $response = $this->middleware->handle($request, $next);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = $response->getData(true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals($originalData, $responseData['data']);
        $this->assertEquals('Request completed successfully', $responseData['message']);
        $this->assertArrayHasKey('timestamp', $responseData);
    }

    #[Test]
    public function it_formats_error_response_correctly(): void
    {
        // Arrange
        $request = Request::create('/test', 'GET');
        $errorMessage = 'Something went wrong';
        $next = function () use ($errorMessage) {
            throw new Exception($errorMessage);
        };

        // Act
        $response = $this->middleware->handle($request, $next);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());

        $responseData = $response->getData(true);
        $this->assertFalse($responseData['success']);
        $this->assertNull($responseData['data']);
        $this->assertEquals($errorMessage, $responseData['message']);
        $this->assertArrayHasKey('timestamp', $responseData);
    }

    #[Test]
    public function it_preserves_custom_status_codes_in_success_response(): void
    {
        // Arrange
        $request = Request::create('/test', 'POST');
        $next = function () {
            return response()->json(['created' => true], 201);
        };

        // Act
        $response = $this->middleware->handle($request, $next);

        // Assert
        $this->assertEquals(201, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertTrue($responseData['success']);
    }

    #[Test]
    public function it_handles_empty_response_data(): void
    {
        // Arrange
        $request = Request::create('/test', 'DELETE');
        $next = function () {
            return response()->json(null, 204);
        };

        // Act
        $response = $this->middleware->handle($request, $next);

        // Assert
        $this->assertEquals(204, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals([], $responseData['data']);
    }
}
