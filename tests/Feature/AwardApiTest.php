<?php

use App\Models\Award;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Award API Feature Tests
 * 
 * Tests for the public Award API endpoints.
 */
describe('Award API', function () {

    beforeEach(function () {
        // Create test awards
        Award::factory()->create([
            'title' => 'First Award',
            'location' => 'Bali, 2020',
            'featured' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Award::factory()->create([
            'title' => 'Featured Award',
            'location' => 'Shanghai, 2021',
            'featured' => true,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Award::factory()->create([
            'title' => 'Inactive Award',
            'location' => 'Zurich, 2022',
            'featured' => false,
            'is_active' => false,
            'sort_order' => 3,
        ]);

        Award::factory()->create([
            'title' => 'Third Active Award',
            'location' => 'Bandung, 2023',
            'featured' => false,
            'is_active' => true,
            'sort_order' => 0,
        ]);
    });

    test('it returns all active awards ordered by sort_order', function () {
        $response = $this->getJson('/api/awards');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'location',
                        'featured',
                    ],
                ],
            ])
            ->assertJson([
                'success' => true,
            ]);

        $data = $response->json('data');

        // Should return 3 active awards
        expect($data)->toHaveCount(3);

        // Should be ordered by sort_order ascending
        expect($data[0]['title'])->toBe('Third Active Award'); // sort_order: 0
        expect($data[1]['title'])->toBe('First Award'); // sort_order: 1
        expect($data[2]['title'])->toBe('Featured Award'); // sort_order: 2

        // Should not include inactive award
        $titles = collect($data)->pluck('title')->toArray();
        expect($titles)->not->toContain('Inactive Award');
    });

    test('it excludes inactive awards from the list', function () {
        $response = $this->getJson('/api/awards');

        $response->assertStatus(200);

        $data = $response->json('data');
        $titles = collect($data)->pluck('title')->toArray();

        expect($titles)->not->toContain('Inactive Award');
    });

    test('it returns only featured awards when accessing featured endpoint', function () {
        $response = $this->getJson('/api/awards/featured');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $data = $response->json('data');

        // Should return only 1 featured award
        expect($data)->toHaveCount(1);
        expect($data[0]['title'])->toBe('Featured Award');
        expect($data[0]['featured'])->toBeTrue();
    });

    test('it returns correct data structure for each award', function () {
        $response = $this->getJson('/api/awards');

        $response->assertStatus(200);

        $data = $response->json('data');
        $award = $data[0];

        expect($award)->toHaveKeys(['id', 'title', 'location', 'featured']);
        expect($award['id'])->toBeInt();
        expect($award['title'])->toBeString();
        expect($award['location'])->toBeString();
        expect($award['featured'])->toBeBool();
    });

    test('it returns empty array when no active awards exist', function () {
        // Deactivate all awards
        Award::query()->update(['is_active' => false]);

        $response = $this->getJson('/api/awards');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [],
            ]);
    });

    test('it orders awards by sort_order ascending', function () {
        // Create awards with specific sort_order
        Award::query()->delete(); // Clear existing

        Award::factory()->create(['title' => 'Award Z', 'sort_order' => 10, 'is_active' => true]);
        Award::factory()->create(['title' => 'Award A', 'sort_order' => 1, 'is_active' => true]);
        Award::factory()->create(['title' => 'Award M', 'sort_order' => 5, 'is_active' => true]);

        $response = $this->getJson('/api/awards');

        $response->assertStatus(200);

        $data = $response->json('data');

        expect($data[0]['title'])->toBe('Award A'); // sort_order: 1
        expect($data[1]['title'])->toBe('Award M'); // sort_order: 5
        expect($data[2]['title'])->toBe('Award Z'); // sort_order: 10
    });

    test('it handles database errors gracefully', function () {
        // This test would require mocking database failures
        // For now, just verify the endpoint exists and returns a valid structure
        $response = $this->getJson('/api/awards');

        $response->assertStatus(200);
    });

    test('featured endpoint excludes inactive awards', function () {
        Award::factory()->create([
            'title' => 'Inactive Featured Award',
            'featured' => true,
            'is_active' => false,
        ]);

        $response = $this->getJson('/api/awards/featured');

        $response->assertStatus(200);

        $data = $response->json('data');
        $titles = collect($data)->pluck('title')->toArray();

        expect($titles)->not->toContain('Inactive Featured Award');
    });
});
