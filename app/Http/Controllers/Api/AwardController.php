<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Award;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

/**
 * Award API Controller
 * 
 * Provides public API endpoints for fetching awards data.
 * Data is cached for 1 hour to improve performance.
 */
class AwardController extends Controller
{
    /**
     * Get all active awards ordered by sort_order.
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @api GET /api/awards
     * @apiDescription Fetch all active awards for frontend display
     * @apiSuccess {boolean} success Response status
     * @apiSuccess {array} data Array of award objects
     * @apiSuccessExample {json} Success-Response:
     * {
     *   "success": true,
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Innovation Excellence Award",
     *       "location": "Bali, 2020",
     *       "featured": false
     *     }
     *   ]
     * }
     */
    public function index(): JsonResponse
    {
        try {
            // Cache the results for 1 hour
            $awards = Cache::remember('awards.active', 3600, function () {
                return Award::active()
                    ->ordered()
                    ->get()
                    ->map(function ($award) {
                        return [
                            'id' => $award->id,
                            'title' => $award->title,
                            'location' => $award->location,
                            'featured' => $award->featured,
                        ];
                    });
            });

            return response()->json([
                'success' => true,
                'data' => $awards,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch awards',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    /**
     * Get featured awards only.
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @api GET /api/awards/featured
     * @apiDescription Fetch only featured awards
     */
    public function featured(): JsonResponse
    {
        try {
            $awards = Cache::remember('awards.featured', 3600, function () {
                return Award::active()
                    ->featured()
                    ->ordered()
                    ->get()
                    ->map(function ($award) {
                        return [
                            'id' => $award->id,
                            'title' => $award->title,
                            'location' => $award->location,
                            'featured' => $award->featured,
                        ];
                    });
            });

            return response()->json([
                'success' => true,
                'data' => $awards,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch featured awards',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }
}
