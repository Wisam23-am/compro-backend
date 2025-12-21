<?php

namespace Tests\Feature;

use App\Models\Principle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Principle API Test
 * 
 * Comprehensive tests for the Principle API endpoints.
 * Tests include successful responses, error handling, and edge cases.
 */
class PrincipleApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Clear cache before each test
        Cache::flush();
    }

    /**
     * Test fetching all active principles.
     *
     * @return void
     */
    public function test_can_fetch_all_active_principles(): void
    {
        // Create test principles
        Principle::factory()->active()->count(3)->create();
        Principle::factory()->inactive()->count(2)->create();

        $response = $this->getJson('/api/principles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'subtitle',
                        'description',
                        'icon',
                        'image',
                        'sort_order',
                    ],
                ],
                'meta' => [
                    'total',
                    'timestamp',
                ],
            ])
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonCount(3, 'data'); // Only active principles
    }

    /**
     * Test that inactive principles are not returned.
     *
     * @return void
     */
    public function test_inactive_principles_are_not_returned(): void
    {
        Principle::factory()->active()->create(['title' => 'Active Principle']);
        Principle::factory()->inactive()->create(['title' => 'Inactive Principle']);

        $response = $this->getJson('/api/principles');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Active Principle'])
            ->assertJsonMissing(['title' => 'Inactive Principle']);
    }

    /**
     * Test that principles are ordered by sort_order.
     *
     * @return void
     */
    public function test_principles_are_ordered_by_sort_order(): void
    {
        Principle::factory()->active()->create(['title' => 'Third', 'sort_order' => 3]);
        Principle::factory()->active()->create(['title' => 'First', 'sort_order' => 1]);
        Principle::factory()->active()->create(['title' => 'Second', 'sort_order' => 2]);

        $response = $this->getJson('/api/principles');

        $data = $response->json('data');

        $this->assertEquals('First', $data[0]['title']);
        $this->assertEquals('Second', $data[1]['title']);
        $this->assertEquals('Third', $data[2]['title']);
    }

    /**
     * Test fetching a specific active principle.
     *
     * @return void
     */
    public function test_can_fetch_single_active_principle(): void
    {
        $principle = Principle::factory()->active()->create([
            'title' => 'Test Principle',
            'description' => 'Test Description',
        ]);

        $response = $this->getJson("/api/principles/{$principle->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'title',
                    'subtitle',
                    'description',
                    'icon',
                    'image',
                    'sort_order',
                ],
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $principle->id,
                    'title' => 'Test Principle',
                    'description' => 'Test Description',
                ],
            ]);
    }

    /**
     * Test fetching an inactive principle returns 404.
     *
     * @return void
     */
    public function test_cannot_fetch_inactive_principle(): void
    {
        $principle = Principle::factory()->inactive()->create();

        $response = $this->getJson("/api/principles/{$principle->id}");

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Principle not found or inactive',
            ]);
    }

    /**
     * Test fetching a non-existent principle returns 404.
     *
     * @return void
     */
    public function test_fetch_non_existent_principle_returns_404(): void
    {
        $response = $this->getJson('/api/principles/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Principle not found or inactive',
            ]);
    }

    /**
     * Test fetching empty principles list.
     *
     * @return void
     */
    public function test_fetch_empty_principles_list(): void
    {
        $response = $this->getJson('/api/principles');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [],
                'meta' => [
                    'total' => 0,
                ],
            ]);
    }

    /**
     * Test fetching principle statistics.
     *
     * @return void
     */
    public function test_can_fetch_principle_statistics(): void
    {
        Principle::factory()->active()->count(5)->create();
        Principle::factory()->inactive()->count(3)->create();

        $response = $this->getJson('/api/principles/stats/overview');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'total',
                    'active',
                    'inactive',
                ],
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'total' => 8,
                    'active' => 5,
                    'inactive' => 3,
                ],
            ]);
    }

    /**
     * Test that API responses are cached.
     *
     * @return void
     */
    public function test_api_responses_are_cached(): void
    {
        Principle::factory()->active()->create();

        // First request - should cache
        $response1 = $this->getJson('/api/principles');
        $response1->assertStatus(200);

        // Verify cache exists
        $this->assertTrue(Cache::has('principles.active'));

        // Create another principle (won't appear due to cache)
        Principle::factory()->active()->create();

        // Second request - should use cache
        $response2 = $this->getJson('/api/principles');
        $response2->assertStatus(200)
            ->assertJsonCount(1, 'data'); // Still showing 1 due to cache
    }

    /**
     * Test API returns proper JSON structure for image and icon URLs.
     *
     * @return void
     */
    public function test_principle_includes_full_asset_urls(): void
    {
        $principle = Principle::factory()->active()->create([
            'icon' => 'principles/icons/test-icon.svg',
            'image' => 'principles/images/test-image.jpg',
        ]);

        $response = $this->getJson("/api/principles/{$principle->id}");

        $response->assertStatus(200);

        $data = $response->json('data');

        // Verify URLs are properly formatted
        $this->assertStringContainsString('storage/principles/icons/test-icon.svg', $data['icon']);
        $this->assertStringContainsString('storage/principles/images/test-image.jpg', $data['image']);
    }

    /**
     * Test API handles principles without images gracefully.
     *
     * @return void
     */
    public function test_principle_without_images_returns_null(): void
    {
        $principle = Principle::factory()->active()->create([
            'icon' => null,
            'image' => null,
        ]);

        $response = $this->getJson("/api/principles/{$principle->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'icon' => null,
                    'image' => null,
                ],
            ]);
    }
}
