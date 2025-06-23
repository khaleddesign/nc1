<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PhotoController;
use App\Http\Controllers\Api\DevisController;
use App\Http\Controllers\Api\EvaluationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Dashboard API
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/recent-activities', [DashboardController::class, 'recentActivities']);
    
    // Notifications API
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    
    // Photos API
    Route::apiResource('photos', PhotoController::class);
    Route::post('/photos/{photo}/toggle-visibility', [PhotoController::class, 'toggleVisibility']);
    Route::get('/photos/by-chantier/{chantier}', [PhotoController::class, 'byChantier']);
    
    // Devis API
    Route::apiResource('devis', DevisController::class);
    Route::post('/devis/{devis}/send', [DevisController::class, 'send']);
    Route::get('/devis/{devis}/pdf', [DevisController::class, 'downloadPdf']);
    
    // Evaluations API
    Route::get('/evaluations', [EvaluationController::class, 'index']);
    Route::post('/evaluations', [EvaluationController::class, 'store']);
    Route::put('/evaluations/{evaluation}', [EvaluationController::class, 'update']);
    Route::get('/evaluations/stats', [EvaluationController::class, 'stats']);
});
