<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminLawyerController;
use App\Http\Controllers\Admin\AdminClientController;
use App\Http\Controllers\Admin\AdminCaseController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminPricingController;
use App\Http\Controllers\Admin\AdminContentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/admin/dashboard');
});

// ─── Admin Routes ────────────────────────────────────────────────────────────
Route::prefix('admin')->group(function () {

    // Auth
    Route::get('/login', [AdminAuthController::class, 'loginPage']);
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::get('/logout', [AdminAuthController::class, 'logout']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Lawyers
    Route::get('/lawyers', [AdminLawyerController::class, 'index']);
    Route::get('/lawyers/create', [AdminLawyerController::class, 'createPage']);
    Route::post('/lawyers/create', [AdminLawyerController::class, 'store']);
    Route::get('/lawyers/{lawyerId}', [AdminLawyerController::class, 'show']);
    Route::post('/lawyers/{lawyerId}/toggle-active', [AdminLawyerController::class, 'toggleActive']);
    Route::post('/lawyers/{lawyerId}/update-profile', [AdminLawyerController::class, 'updateProfile']);

    // Clients
    Route::get('/clients', [AdminClientController::class, 'index']);
    Route::get('/clients/{clientId}', [AdminClientController::class, 'show']);
    Route::post('/clients/{clientId}/toggle-active', [AdminClientController::class, 'toggleActive']);

    // Cases
    Route::get('/cases', [AdminCaseController::class, 'index']);
    Route::get('/cases/{caseId}', [AdminCaseController::class, 'show']);
    Route::post('/cases/{caseId}/disbursement/process', [AdminCaseController::class, 'processDisbursement']);
    Route::post('/cases/{caseId}/disbursement/complete', [AdminCaseController::class, 'completeDisbursement']);

    // Categories
    Route::get('/categories', [AdminCategoryController::class, 'index']);
    Route::get('/categories/create', [AdminCategoryController::class, 'createPage']);
    Route::post('/categories/create', [AdminCategoryController::class, 'store']);
    Route::get('/categories/{catId}/edit', [AdminCategoryController::class, 'editPage']);
    Route::post('/categories/{catId}/edit', [AdminCategoryController::class, 'update']);
    Route::post('/categories/{catId}/toggle', [AdminCategoryController::class, 'toggle']);

    // Pricing
    Route::get('/pricing', [AdminPricingController::class, 'index']);
    Route::post('/pricing', [AdminPricingController::class, 'store']);

    // Content
    Route::get('/content/{slug}', [AdminContentController::class, 'edit']);
    Route::post('/content/{slug}', [AdminContentController::class, 'update']);
});
