<?php

use App\Http\Controllers\Api\PrincipleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Public Principle API Endpoints
 * 
 * These endpoints are publicly accessible and return active principles.
 * No authentication required.
 */
Route::prefix('principles')->name('api.principles.')->group(function () {
    // Get all active principles
    Route::get('/', [PrincipleController::class, 'index'])->name('index');

    // Get a specific principle by ID
    Route::get('/{id}', [PrincipleController::class, 'show'])->name('show');

    // Get principle statistics
    Route::get('/stats/overview', [PrincipleController::class, 'stats'])->name('stats');
});
