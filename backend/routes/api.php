<?php

use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PageSectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make sure you run 
| `php artisan install:api` if you haven't already to register this file.
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Assets
Route::get('/assets', [AssetController::class, 'index']);
Route::post('/assets', [AssetController::class, 'store']);
Route::delete('/assets/{id}', [AssetController::class, 'destroy']);

// Pages
Route::get('/pages', [PageController::class, 'index']); // List all
Route::get('/pages/{slug}', [PageController::class, 'show']); // Show one

// Sections
Route::put('/sections/{id}/assets', [PageSectionController::class, 'updateAssets']);
