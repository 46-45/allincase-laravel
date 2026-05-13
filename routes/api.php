<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\LawyerController;
use App\Http\Controllers\Api\V1\CaseController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\ChatController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\ContentController;
use App\Http\Controllers\Api\V1\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes — /api/v1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // ─── Auth (public) ───────────────────────────────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });

    // ─── Content (public) ────────────────────────────────────────────────────
    Route::get('/content/{slug}', [ContentController::class, 'show']);

    // ─── Categories (public) ─────────────────────────────────────────────────
    Route::get('/categories', [CategoryController::class, 'index']);

    // ─── Lawyer public profile ───────────────────────────────────────────────
    Route::get('/lawyers/{lawyerId}/public', [LawyerController::class, 'publicProfile']);

    // ─── Payment webhook (no auth) ──────────────────────────────────────────
    Route::post('/payments/webhook', [PaymentController::class, 'webhook']);
    Route::get('/payments/finish', [PaymentController::class, 'finish']);

    // ─── Authenticated routes ────────────────────────────────────────────────
    Route::middleware('auth:api')->group(function () {

        // Auth
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/change-password', [AuthController::class, 'changePassword']);

        // Users
        Route::patch('/users/me', [UserController::class, 'updateProfile']);
        Route::post('/users/me/avatar', [UserController::class, 'uploadAvatar']);
        Route::post('/users/me/device-token', [UserController::class, 'registerDeviceToken']);
        Route::delete('/users/me/device-token', [UserController::class, 'removeDeviceToken']);
        Route::delete('/users/me', [UserController::class, 'deleteAccount']);

        // Lawyers
        Route::get('/lawyers/me/profile', [LawyerController::class, 'getMyProfile']);
        Route::patch('/lawyers/me/profile', [LawyerController::class, 'updateMyProfile']);
        Route::patch('/lawyers/me/bank', [LawyerController::class, 'updateBankInfo']);
        Route::post('/lawyers/me/availability', [LawyerController::class, 'setAvailability']);
        Route::get('/lawyers/me/documents', [LawyerController::class, 'getMyDocuments']);
        Route::post('/lawyers/me/documents', [LawyerController::class, 'uploadDocument']);
        Route::delete('/lawyers/me/documents/{docId}', [LawyerController::class, 'deleteDocument']);

        // Cases
        Route::post('/cases', [CaseController::class, 'store']);
        Route::get('/cases', [CaseController::class, 'index']);
        Route::get('/cases/lawyer/incoming', [CaseController::class, 'incoming']);
        Route::get('/cases/{caseId}', [CaseController::class, 'show']);
        Route::post('/cases/{caseId}/accept', [CaseController::class, 'accept']);
        Route::post('/cases/{caseId}/reject', [CaseController::class, 'reject']);
        Route::post('/cases/{caseId}/cancel', [CaseController::class, 'cancel']);
        Route::post('/cases/{caseId}/complete', [CaseController::class, 'complete']);

        // Payments
        Route::post('/payments/snap', [PaymentController::class, 'getSnapToken']);
        Route::post('/payments/simulate-success/{caseId}', [PaymentController::class, 'simulateSuccess']);

        // Chat
        Route::get('/chat/conversations', [ChatController::class, 'conversations']);
        Route::get('/chat/{caseId}/messages', [ChatController::class, 'getMessages']);
        Route::post('/chat/{caseId}/messages', [ChatController::class, 'sendMessage']);

        // Reviews
        Route::post('/reviews', [ReviewController::class, 'store']);

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
        Route::post('/notifications/mark-read', [NotificationController::class, 'markRead']);
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead']);
    });
});
