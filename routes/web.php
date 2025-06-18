<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    ClientController,
    ProjectController,
    TeamMemberController,
    ChatController,
    InvoiceController,
    InstallController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// صفحة التثبيت
Route::get('/install', [InstallController::class, 'index'])->name('install');
Route::post('/install/process', [InstallController::class, 'process'])->name('install.process');

// المصادقة
Auth::routes(['register' => false]);

// المسارات الأساسية (تتطلب مصادقة)
Route::middleware(['auth', 'check.installation'])->group(function () {
    // لوحة التحكم
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // العملاء
    Route::resource('clients', ClientController::class)->except(['show']);
    Route::get('clients/{client}/activity', [ClientController::class, 'activity'])->name('clients.activity');
    
    // المشاريع
    Route::resource('projects', ProjectController::class);
    Route::post('projects/{project}/update-status', [ProjectController::class, 'updateStatus'])
         ->name('projects.update-status');
    
    // الفريق
    Route::resource('team', TeamMemberController::class)->except(['show']);
    Route::post('team/{member}/toggle-status', [TeamMemberController::class, 'toggleStatus'])
         ->name('team.toggle-status');
    
    // الدردشة
    Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('chat/history/{client}', [ChatController::class, 'getHistory'])->name('chat.history');
    
    // الفواتير
    Route::resource('invoices', InvoiceController::class);
    Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])
         ->name('invoices.download');
    Route::post('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markAsPaid'])
         ->name('invoices.mark-paid');
    
    // الإعدادات
    Route::view('settings', 'settings')->name('settings')->middleware('can:manage-settings');
});

// صفحة الترحيب (لغير المسجلين)
Route::view('/welcome', 'welcome')->name('welcome');