<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OCRController;

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

// OCR Processing Routes
Route::post('/ocr/process', [OCRController::class, 'process']);
Route::post('/vision/process-form', [App\Http\Controllers\Api\GoogleVisionController::class, 'processForm']);
Route::get('/vision/check-status/{jobId}', [App\Http\Controllers\Api\GoogleVisionController::class, 'checkStatus']);

// Public routes
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);

// Senior Authentication Routes (for Eldera mobile app)
Route::post('/senior/login', [App\Http\Controllers\Api\SeniorAuthController::class, 'login']);
Route::post('/senior/direct-login', [App\Http\Controllers\Api\SeniorAuthController::class, 'directLogin']);
Route::post('/senior/register', [App\Http\Controllers\Api\SeniorAuthController::class, 'register']);

// Public Announcements API (for Eldera app)
Route::get('/announcements', [App\Http\Controllers\Api\AnnouncementController::class, 'index']);
Route::get('/announcements/{id}', [App\Http\Controllers\Api\AnnouncementController::class, 'show']);

// Public Events API (for calendar functionality)
Route::get('/events', [App\Http\Controllers\Api\EventController::class, 'index']);
Route::get('/events/{id}', [App\Http\Controllers\Api\EventController::class, 'show']);

// Public Senior Search API (for participant management)
Route::get('/seniors/search', [App\Http\Controllers\Api\SeniorController::class, 'search']);

// Protected routes with rate limiting
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    // User authentication
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
    
    // Senior authentication (for Eldera mobile app)
    Route::get('/senior/profile', [App\Http\Controllers\Api\SeniorAuthController::class, 'profile']);
    Route::post('/senior/logout', [App\Http\Controllers\Api\SeniorAuthController::class, 'logout']);
    
    // Senior Citizens
    Route::get('/seniors', [App\Http\Controllers\Api\SeniorController::class, 'index']);
    Route::get('/seniors/{id}', [App\Http\Controllers\Api\SeniorController::class, 'show']);
    Route::put('/seniors/{id}', [App\Http\Controllers\Api\SeniorController::class, 'update']);
    Route::post('/seniors/{id}/documents', [App\Http\Controllers\Api\SeniorController::class, 'uploadDocument']);
    Route::get('/seniors/{id}/documents', [App\Http\Controllers\Api\SeniorController::class, 'getDocuments']);
    
    // Applications
    Route::post('/applications/id', [App\Http\Controllers\Api\ApplicationController::class, 'storeIdApplication']);
    Route::post('/applications/pension', [App\Http\Controllers\Api\ApplicationController::class, 'storePensionApplication']);
    Route::post('/applications/benefits', [App\Http\Controllers\Api\ApplicationController::class, 'storeBenefitsApplication']);
    Route::get('/applications/status/{id}', [App\Http\Controllers\Api\ApplicationController::class, 'checkStatus']);
    
    // Documents
    Route::post('/documents/upload', [App\Http\Controllers\Api\DocumentController::class, 'upload']);
    Route::post('/documents/upload-multiple', [App\Http\Controllers\Api\DocumentController::class, 'uploadMultiple']);
    Route::get('/documents', [App\Http\Controllers\Api\DocumentController::class, 'index']);
    Route::delete('/documents/{id}', [App\Http\Controllers\Api\DocumentController::class, 'destroy']);
    
    // Notifications
    Route::get('/notifications', [App\Http\Controllers\Api\NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [App\Http\Controllers\Api\NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [App\Http\Controllers\Api\NotificationController::class, 'markAllAsRead']);
    Route::get('/notifications/stats', [App\Http\Controllers\Api\NotificationController::class, 'stats']);
    Route::delete('/notifications/{id}', [App\Http\Controllers\Api\NotificationController::class, 'destroy']);
});