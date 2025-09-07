<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WeddingControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_index_requires_authentication(): void
    {
        // Arrange
        Wedding::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/v1/weddings');

        // Assert
        $response->assertStatus(401);
        $payload = $response->json();
        $this->assertFalse($payload['success']);
        $this->assertNull($payload['data']);
        $this->assertEquals('Unauthenticated.', $payload['message']);
        $this->assertArrayHasKey('timestamp', $payload);
    }

    #[Test]
    public function test_index_returns_paginated_list_with_meta_and_links(): void
    {
        // Arrange
        Sanctum::actingAs(User::factory()->create());
        Wedding::factory()->count(30)->create();

        // Act
        $response = $this->getJson('/api/v1/weddings?per_page=10&page=2');

        // Assert
        $response->assertOk();
        $payload = $response->json();

        $this->assertTrue($payload['success']);
        $this->assertArrayHasKey('items', $payload['data']);
        $this->assertCount(10, $payload['data']['items']);

        $meta = $payload['data']['meta'];
        $this->assertSame(2, $meta['current_page']);
        $this->assertSame(10, $meta['per_page']);
        $this->assertSame(30, $meta['total']);
        $this->assertSame(3, $meta['last_page']);

        $links = $payload['data']['links'];
        $this->assertNotNull($links['first']);
        $this->assertNotNull($links['last']);
        $this->assertNotNull($links['prev']);
        $this->assertNotNull($links['next']);

        // Check item shape (resource mapped, no inner data)
        $item = $payload['data']['items'][0];
        $this->assertArrayHasKey('id', $item);
        $this->assertArrayHasKey('name', $item);
        $this->assertArrayHasKey('slug', $item);
        $this->assertArrayHasKey('event_date', $item);
        $this->assertArrayHasKey('location', $item);
        $this->assertArrayHasKey('description', $item);
        $this->assertArrayHasKey('guests_count', $item);
        $this->assertArrayHasKey('vendors_count', $item);
        $this->assertArrayHasKey('timeline_items_count', $item);
    }

    #[Test]
    public function test_index_filters_by_exact_event_date(): void
    {
        // Arrange
        Sanctum::actingAs(User::factory()->create());
        Wedding::factory()->create(['event_date' => '2025-01-01']);
        Wedding::factory()->create(['event_date' => '2025-01-02']);

        // Act
        $response = $this->getJson('/api/v1/weddings?event_date=2025-01-02');

        // Assert
        $response->assertOk();
        $payload = $response->json();
        $this->assertTrue($payload['success']);
        $this->assertArrayHasKey('items', $payload['data']);
        $this->assertCount(1, $payload['data']['items']);
        $this->assertSame('2025-01-02', $payload['data']['items'][0]['event_date']);
    }

    #[Test]
    public function test_index_filters_by_date_range_and_location(): void
    {
        // Arrange
        Sanctum::actingAs(User::factory()->create());
        Wedding::factory()->create(['event_date' => '2025-03-10', 'location' => 'Madrid']);
        Wedding::factory()->create(['event_date' => '2025-03-15', 'location' => 'Barcelona']);
        Wedding::factory()->create(['event_date' => '2025-03-20', 'location' => 'Madrid Centro']);

        // Act
        $response = $this->getJson('/api/v1/weddings?event_date_from=2025-03-11&event_date_to=2025-03-25&location=Madrid');

        // Assert
        $response->assertOk();
        $payload = $response->json();
        $this->assertTrue($payload['success']);
        $this->assertArrayHasKey('items', $payload['data']);
        $this->assertCount(1, $payload['data']['items']);
        $this->assertSame('2025-03-20', $payload['data']['items'][0]['event_date']);
        $this->assertStringContainsString('Madrid', $payload['data']['items'][0]['location']);
    }

    #[Test]
    public function test_show_returns_wedding_resource(): void
    {
        // Arrange
        Sanctum::actingAs(User::factory()->create());
        $wedding = Wedding::factory()->create([
            'event_date' => '2025-05-05',
            'location' => 'Sevilla',
        ]);

        // Act
        $response = $this->getJson('/api/v1/weddings/'.$wedding->id);

        // Assert
        $response->assertOk();
        $payload = $response->json();
        $this->assertTrue($payload['success']);
        $this->assertSame($wedding->id, $payload['data']['id']);
        $this->assertSame('2025-05-05', $payload['data']['event_date']);
        $this->assertSame('Sevilla', $payload['data']['location']);
    }

    #[Test]
    public function test_store_creates_wedding_and_returns_201(): void
    {
        // Arrange
        Sanctum::actingAs(User::factory()->create());
        $payload = [
            'name' => 'Boda de Ada y Bob',
            'slug' => 'boda-de-ada-y-bob',
            'event_date' => '2025-10-10',
            'location' => 'Madrid',
            'description' => 'Ceremonia civil',
        ];

        // Act
        $response = $this->postJson('/api/v1/weddings', $payload);

        // Assert
        $response->assertCreated();
        $res = $response->json();
        $this->assertTrue($res['success']);
        $this->assertSame('Boda de Ada y Bob', $res['data']['name']);
        $this->assertSame('boda-de-ada-y-bob', $res['data']['slug']);
        $this->assertDatabaseHas('weddings', ['slug' => 'boda-de-ada-y-bob']);
    }

    #[Test]
    public function test_store_generates_slug_when_missing(): void
    {
        // Arrange
        Sanctum::actingAs(User::factory()->create());
        $payload = [
            'name' => 'Boda de Carol y Dave',
            'event_date' => '2025-11-11',
        ];

        // Act
        $response = $this->postJson('/api/v1/weddings', $payload);

        // Assert
        $response->assertCreated();
        $res = $response->json();
        $this->assertTrue($res['success']);
        $this->assertNotEmpty($res['data']['slug']);
        $this->assertDatabaseHas('weddings', ['id' => $res['data']['id'], 'slug' => $res['data']['slug']]);
    }

    #[Test]
    public function test_update_modifies_fields(): void
    {
        // Arrange
        Sanctum::actingAs(User::factory()->create());
        $wedding = Wedding::factory()->create([
            'name' => 'Original',
            'slug' => 'original',
        ]);

        // Act
        $response = $this->putJson('/api/v1/weddings/'.$wedding->id, [
            'name' => 'Actualizada',
            'slug' => 'actualizada',
        ]);

        // Assert
        $response->assertOk();
        $res = $response->json();
        $this->assertTrue($res['success']);
        $this->assertSame('Actualizada', $res['data']['name']);
        $this->assertSame('actualizada', $res['data']['slug']);
        $this->assertDatabaseHas('weddings', ['id' => $wedding->id, 'name' => 'Actualizada', 'slug' => 'actualizada']);
    }

    #[Test]
    public function test_destroy_deletes_and_returns_204(): void
    {
        // Arrange
        Sanctum::actingAs(User::factory()->create());
        $wedding = Wedding::factory()->create();

        // Act
        $response = $this->deleteJson('/api/v1/weddings/'.$wedding->id);

        // Assert
        $response->assertNoContent();
        $this->assertSame('', $response->getContent());
        $this->assertDatabaseMissing('weddings', ['id' => $wedding->id]);
    }
}
