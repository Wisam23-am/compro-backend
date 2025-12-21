<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Principle;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Principle API Controller
 * 
 * Handles public REST API endpoints for fetching principles.
 * Returns JSON responses with proper error handling and caching.
 */
class PrincipleController extends Controller
{
    /**
     * Get all active principles.
     * 
     * Returns all active principles ordered by sort_order.
     * Results are cached for 1 hour for better performance.
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @response {
     *   "success": true,
     *   "message": "Principles retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Innovation First",
     *       "subtitle": "Leading with creativity",
     *       "description": "We prioritize innovative solutions...",
     *       "icon": "http://example.com/storage/principles/icons/icon.svg",
     *       "image": "http://example.com/storage/principles/images/image.jpg",
     *       "sort_order": 1
     *     }
     *   ],
     *   "meta": {
     *     "total": 5,
     *     "timestamp": "2024-12-22T10:30:00.000000Z"
     *   }
     * }
     */
    public function index(): JsonResponse
    {
        try {
            // Cache the results for 1 hour to improve performance
            $principles = Cache::remember('principles.active', 3600, function () {
                return Principle::active()
                    ->ordered('asc')
                    ->get()
                    ->map(function ($principle) {
                        return [
                            'id' => $principle->id,
                            'title' => $principle->title,
                            'subtitle' => $principle->subtitle,
                            'description' => $principle->description,
                            'icon' => $principle->icon_url,
                            'image' => $principle->image_url,
                            'sort_order' => $principle->sort_order,
                        ];
                    });
            });

            return response()->json([
                'success' => true,
                'message' => 'Principles retrieved successfully',
                'data' => $principles,
                'meta' => [
                    'total' => $principles->count(),
                    'timestamp' => now()->toISOString(),
                ],
            ], 200);

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Failed to retrieve principles: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve principles',
                'error' => config('app.debug') ? $e->getMessage() : 'An internal error occurred',
            ], 500);
        }
    }

    /**
     * Get a specific principle by ID.
     * 
     * Returns a single principle if it's active.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @response {
     *   "success": true,
     *   "message": "Principle retrieved successfully",
     *   "data": {
     *     "id": 1,
     *     "title": "Innovation First",
     *     "subtitle": "Leading with creativity",
     *     "description": "We prioritize innovative solutions...",
     *     "icon": "http://example.com/storage/principles/icons/icon.svg",
     *     "image": "http://example.com/storage/principles/images/image.jpg",
     *     "sort_order": 1
     *   }
     * }
     */
    public function show(int $id): JsonResponse
    {
        try {
            // Cache individual principle for 1 hour
            $principle = Cache::remember("principles.{$id}", 3600, function () use ($id) {
                return Principle::active()->find($id);
            });

            if (!$principle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Principle not found or inactive',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Principle retrieved successfully',
                'data' => [
                    'id' => $principle->id,
                    'title' => $principle->title,
                    'subtitle' => $principle->subtitle,
                    'description' => $principle->description,
                    'icon' => $principle->icon_url,
                    'image' => $principle->image_url,
                    'sort_order' => $principle->sort_order,
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve principle: ' . $e->getMessage(), [
                'principle_id' => $id,
                'exception' => $e,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve principle',
                'error' => config('app.debug') ? $e->getMessage() : 'An internal error occurred',
            ], 500);
        }
    }

    /**
     * Get principles count statistics.
     * 
     * Returns statistical information about principles.
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @response {
     *   "success": true,
     *   "message": "Statistics retrieved successfully",
     *   "data": {
     *     "total": 10,
     *     "active": 8,
     *     "inactive": 2
     *   }
     * }
     */
    public function stats(): JsonResponse
    {
        try {
            // Cache stats for 30 minutes
            $stats = Cache::remember('principles.stats', 1800, function () {
                return [
                    'total' => Principle::count(),
                    'active' => Principle::active()->count(),
                    'inactive' => Principle::inactive()->count(),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Statistics retrieved successfully',
                'data' => $stats,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve principle statistics: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics',
                'error' => config('app.debug') ? $e->getMessage() : 'An internal error occurred',
            ], 500);
        }
    }
}
