<?php

use App\Http\API\Controllers\AddressController;
use App\Http\API\Controllers\AuditLogController;
use App\Http\API\Controllers\ClaimController;
use App\Http\API\Controllers\EmailTemplateController;
use App\Http\API\Controllers\InsuranceCategoryController;
use App\Http\API\Controllers\KycVerificationController;
use App\Http\API\Controllers\PolicyController;
use App\Http\API\Controllers\ProviderBankDetailController;
use App\Http\API\Controllers\ProviderController;
use App\Http\API\Controllers\ProviderDocumentController;
use App\Http\API\Controllers\RefundController;
use App\Http\API\Controllers\SentEmailController;
use App\Http\API\Controllers\SystemSettingController;
use App\Http\API\Controllers\TransactionController;
use App\Http\API\Controllers\UserController;
use App\Http\API\Controllers\UserPolicyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\NotificationController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Authenticated routes
Route::middleware(['auth:sanctum'])->group(function () {
    // User routes
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // User profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/API', [UserController::class, 'showProfile']);
        Route::put('/API', [UserController::class, 'updateProfile']);
        Route::post('/change-password', [UserController::class, 'changePassword']);
        Route::post('/upload-avatar', [UserController::class, 'uploadAvatar']);
    });
    // Addresses
    Route::apiResource('addresses', AddressController::class)->except(['index']);
    Route::get('users/{user}/addresses', [AddressController::class, 'index']);
        // Providers
    Route::apiResource('providers', ProviderController::class);
    Route::post('providers/{provider}/approve', [ProviderController::class, 'approve']);
        // Provider bank details
    Route::prefix('providers/{provider}')->group(function () {
        Route::get('bank-details', [ProviderBankDetailController::class, 'show']);
        Route::post('bank-details', [ProviderBankDetailController::class, 'storeOrUpdate']);
        Route::post('bank-details/verify', [ProviderBankDetailController::class, 'verify']);
        Route::delete('bank-details', [ProviderBankDetailController::class, 'destroy']);
    });
    // Provider documents
    Route::apiResource('providers.documents', ProviderDocumentController::class)->shallow();
    Route::post('documents/{document}/approve', [ProviderDocumentController::class, 'approve']);
    Route::post('documents/{document}/reject', [ProviderDocumentController::class, 'reject']);
        // Insurance categories
    Route::apiResource('insurance-categories', InsuranceCategoryController::class);
    Route::post('insurance-categories/{category}/toggle-active', [InsuranceCategoryController::class, 'toggleActive']);
        // Policies
    Route::apiResource('policies', PolicyController::class);
    Route::post('policies/{policy}/approve', [PolicyController::class, 'approve']);
    Route::post('policies/{policy}/toggle-active', [PolicyController::class, 'toggleActive']);
        // User policies
    Route::apiResource('user-policies', UserPolicyController::class)->except(['index']);
    Route::get('users/{user}/user-policies', [UserPolicyController::class, 'index']);
    Route::post('user-policies/{userPolicy}/cancel', [UserPolicyController::class, 'cancel']);
    Route::post('user-policies/{userPolicy}/renew', [UserPolicyController::class, 'renew']);
        // Claims
    Route::apiResource('claims', ClaimController::class)->except(['index', 'store']);
    Route::get('user-policies/{userPolicy}/claims', [ClaimController::class, 'index']);
    Route::post('user-policies/{userPolicy}/claims', [ClaimController::class, 'store']);
    Route::post('claims/{claim}/process', [ClaimController::class, 'process']);
        // Transactions
    Route::apiResource('transactions', TransactionController::class)->except(['index', 'store']);
    Route::get('user-policies/{userPolicy}/transactions', [TransactionController::class, 'index']);
    Route::post('user-policies/{userPolicy}/transactions', [TransactionController::class, 'store']);
        // Notifications
    Route::apiResource('notifications', NotificationController::class)->except(['store']);
    Route::get('users/{user}/notifications', [NotificationController::class, 'index']);
    Route::post('notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead']);
    Route::post('users/{user}/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
        // Email templates
    Route::apiResource('email-templates', EmailTemplateController::class);

    // Sent emails
    Route::apiResource('email-templates.sent-emails', SentEmailController::class)->shallow();

    // Audit logs
    Route::get('audit-logs', [AuditLogController::class, 'index']);
    Route::get('audit-logs/{log}', [AuditLogController::class, 'show']);
    Route::get('audit-logs/filter/entity', [AuditLogController::class, 'filterByEntity']);
    Route::get('audit-logs/filter/user', [AuditLogController::class, 'filterByUser']);
        // System settings
    Route::apiResource('system-settings', SystemSettingController::class)->except(['store']);
    Route::get('system-settings/key/{key}', [SystemSettingController::class, 'getByKey']);
    Route::put('system-settings/key/{key}', [SystemSettingController::class, 'updateByKey']);

    // KYC verifications
    Route::apiResource('kyc-verifications', KycVerificationController::class)->except(['index', 'store']);
    Route::get('users/{user}/kyc-verifications', [KycVerificationController::class, 'index']);
    Route::post('users/{user}/kyc-verifications', [KycVerificationController::class, 'store']);
    Route::get('users/{user}/kyc-status', [KycVerificationController::class, 'status']);
    Route::post('kyc-verifications/{verification}/process', [KycVerificationController::class, 'process']);
       // Refunds
    Route::apiResource('refunds', RefundController::class)->except(['index', 'store']);
    Route::get('transactions/{transaction}/refunds', [RefundController::class, 'index']);
    Route::post('transactions/{transaction}/refunds', [RefundController::class, 'store']);
    Route::post('refunds/{refund}/process', [RefundController::class, 'process']);

    // Admin-only routes
    Route::middleware(['can:admin'])->group(function () {
        Route::apiResource('users', UserController::class);
        Route::post('users/{user}/change-status', [UserController::class, 'changeStatus']);
    });
    });