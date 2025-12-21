<?php

use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Team API Tests
 * 
 * Test suite for Team API endpoints ensuring proper functionality,
 * response formats, and data filtering.
 */

describe('Team API', function () {

    describe('GET /api/team', function () {

        it('returns active team members only', function () {
            // Create test data
            Team::factory()->active()->count(3)->create();
            Team::factory()->inactive()->count(2)->create();

            $response = $this->getJson('/api/team');

            $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'position',
                            'location',
                            'image',
                            'sort_order',
                        ]
                    ],
                    'count'
                ])
                ->assertJson([
                    'success' => true,
                    'count' => 3, // Only active members
                ]);
        });

        it('returns team members ordered by sort_order', function () {
            // Create members with specific sort orders
            Team::factory()->active()->sortOrder(3)->create(['name' => 'Third']);
            Team::factory()->active()->sortOrder(1)->create(['name' => 'First']);
            Team::factory()->active()->sortOrder(2)->create(['name' => 'Second']);

            $response = $this->getJson('/api/team');

            $response->assertStatus(200);

            $data = $response->json('data');
            expect($data[0]['name'])->toBe('First');
            expect($data[1]['name'])->toBe('Second');
            expect($data[2]['name'])->toBe('Third');
        });

        it('returns empty array when no active members exist', function () {
            Team::factory()->inactive()->count(3)->create();

            $response = $this->getJson('/api/team');

            $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'count' => 0,
                    'data' => []
                ]);
        });

        it('includes image_url in response', function () {
            Team::factory()->active()->create([
                'image' => 'team-members/john-doe.jpg'
            ]);

            $response = $this->getJson('/api/team');

            $response->assertStatus(200);

            $data = $response->json('data');
            expect($data[0]['image'])->toContain('storage/team-members/john-doe.jpg');
        });
    });

    describe('GET /api/team/{id}', function () {

        it('returns a specific active team member', function () {
            $member = Team::factory()->active()->create([
                'name' => 'John Doe',
                'position' => 'Senior Developer',
            ]);

            $response = $this->getJson("/api/team/{$member->id}");

            $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'id' => $member->id,
                        'name' => 'John Doe',
                        'position' => 'Senior Developer',
                    ]
                ]);
        });

        it('returns 404 for inactive team member', function () {
            $member = Team::factory()->inactive()->create();

            $response = $this->getJson("/api/team/{$member->id}");

            $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Team member not found or is inactive',
                ]);
        });

        it('returns 404 for non-existent team member', function () {
            $response = $this->getJson('/api/team/999999');

            $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                ]);
        });

        it('includes timestamps in response', function () {
            $member = Team::factory()->active()->create();

            $response = $this->getJson("/api/team/{$member->id}");

            $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'created_at',
                        'updated_at',
                    ]
                ]);
        });
    });

    describe('GET /api/team/stats/overview', function () {

        it('returns correct team statistics', function () {
            Team::factory()->active()->count(7)->create();
            Team::factory()->inactive()->count(3)->create();

            $response = $this->getJson('/api/team/stats/overview');

            $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'total' => 10,
                        'active' => 7,
                        'inactive' => 3,
                        'percentage_active' => 70.0,
                    ]
                ]);
        });

        it('handles zero members correctly', function () {
            $response = $this->getJson('/api/team/stats/overview');

            $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'total' => 0,
                        'active' => 0,
                        'inactive' => 0,
                        'percentage_active' => 0,
                    ]
                ]);
        });

        it('calculates percentage correctly with decimal precision', function () {
            Team::factory()->active()->count(2)->create();
            Team::factory()->inactive()->count(1)->create();

            $response = $this->getJson('/api/team/stats/overview');

            $response->assertStatus(200);

            $percentage = $response->json('data.percentage_active');
            expect($percentage)->toBe(66.67);
        });
    });

    describe('API Response Format', function () {

        it('always includes success flag and message', function () {
            Team::factory()->active()->create();

            $response = $this->getJson('/api/team');

            $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                ]);
        });

        it('includes proper error structure on failure', function () {
            // Force an error by using invalid ID format (if your app validates)
            $response = $this->getJson('/api/team/invalid-id');

            expect($response->json())->toHaveKey('success');
        });
    });
});
