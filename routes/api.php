<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    WhatsAppApiController,
    ProjectApiController,
    ClientApiController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->group(function () {
    // واتساب API
    Route::prefix('whatsapp')->group(function () {
        Route::post('init-session', [WhatsAppApiController::class, 'initSession']);
        Route::post('send-message', [WhatsAppApiController::class, 'sendMessage']);
        Route::get('qr-code', [WhatsAppApiController::class, 'getQrCode']);
    });
    
    // مشاريع API
    Route::get('projects/calendar', [ProjectApiController::class, 'calendarData']);
    Route::get('projects/{project}/timeline', [ProjectApiController::class, 'timeline']);
    
    // عملاء API
    Route::get('clients/search', [ClientApiController::class, 'search']);
});

// مسارات عامة (بدون مصادقة)
Route::prefix('v1')->group(function () {
    Route::post('webhook/whatsapp', [WhatsAppApiController::class, 'webhook']);
    Route::get('public/invoices/{invoice}', [InvoiceController::class, 'publicShow']);
});