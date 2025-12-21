<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\JsonResponse;

/**
 * Team API Controller
 * 
 * Provides REST API endpoints for retrieving team member data.
 */
class TeamController extends Controller
{
    /**
     * Get all active team members ordered by sort_order.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $team = Team::active()
                ->ordered()
                ->get()
                ->map(function ($member) {
                    return [
                        'id' => $member->id,
                        'name' => $member->name,
                        'position' => $member->position,
                        'location' => $member->location,
                        'image' => $member->image_url,
                        'sort_order' => $member->sort_order,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Team members retrieved successfully',
                'data' => $team,
                'count' => $team->count(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve team members',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred',
            ], 500);
        }
    }

    /**
     * Get a specific team member by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $member = Team::active()->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Team member retrieved successfully',
                'data' => [
                    'id' => $member->id,
                    'name' => $member->name,
                    'position' => $member->position,
                    'location' => $member->location,
                    'image' => $member->image_url,
                    'sort_order' => $member->sort_order,
                    'created_at' => $member->created_at->toISOString(),
                    'updated_at' => $member->updated_at->toISOString(),
                ],
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Team member not found or is inactive',
                'error' => 'The requested team member does not exist or is not currently active',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve team member',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred',
            ], 500);
        }
    }

    /**
     * Get team statistics.
     *
     * @return JsonResponse
     */
    public function stats(): JsonResponse
    {
        try {
            $totalMembers = Team::count();
            $activeMembers = Team::active()->count();
            $inactiveMembers = $totalMembers - $activeMembers;

            return response()->json([
                'success' => true,
                'message' => 'Team statistics retrieved successfully',
                'data' => [
                    'total' => $totalMembers,
                    'active' => $activeMembers,
                    'inactive' => $inactiveMembers,
                    'percentage_active' => $totalMembers > 0 ? round(($activeMembers / $totalMembers) * 100, 2) : 0,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve team statistics',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred',
            ], 500);
        }
    }
}
