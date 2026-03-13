<?php

use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\CoverageRequestController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\IspManagementController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PurchaseOrderController as AdminPurchaseOrderController;
use App\Http\Controllers\Admin\ReportingController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Isp\CoverageController as IspCoverageController;
use App\Http\Controllers\Isp\DashboardController as IspDashboardController;
use App\Http\Controllers\Isp\PurchaseOrderController as IspPurchaseOrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Manage ISP
    Route::get('/isp', [IspManagementController::class, 'index'])->name('admin.isp.index');
    Route::post('/isp', [IspManagementController::class, 'store'])->name('admin.isp.store');
    Route::put('/isp/{isp_code}', [IspManagementController::class, 'update'])->name('admin.isp.update');
    Route::delete('/isp/{isp_code}', [IspManagementController::class, 'destroy'])->name('admin.isp.destroy');
    Route::delete('/isp-clear', [IspManagementController::class, 'clearAll'])->name('admin.isp.clear');
    Route::get('/isp/export', [IspManagementController::class, 'export'])->name('admin.isp.export');
    Route::post('/isp/import', [IspManagementController::class, 'import'])->name('admin.isp.import');
    Route::get('/isp/template', [IspManagementController::class, 'downloadTemplate'])->name('admin.isp.template');

    // Manage Users (Super Admin only)
    Route::middleware(['superadmin'])->group(function () {
        Route::get('/users/export', [UserManagementController::class, 'export'])->name('admin.users.export');
        Route::post('/users/import', [UserManagementController::class, 'import'])->name('admin.users.import');
        Route::get('/users/template', [UserManagementController::class, 'downloadTemplate'])->name('admin.users.template');
        Route::get('/users', [UserManagementController::class, 'index'])->name('admin.users.index');
        Route::post('/users', [UserManagementController::class, 'store'])->name('admin.users.store');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');
        Route::delete('/users-clear', [UserManagementController::class, 'clearAll'])->name('admin.users.clear');
        Route::delete('/purchase-orders-clear', [AdminPurchaseOrderController::class, 'clearAll'])->name('admin.purchase-orders.clear');
    });

    // Check Coverage
    Route::get('/coverage/export', [CoverageRequestController::class, 'export'])->name('admin.coverage.export');
    Route::post('/coverage/import', [CoverageRequestController::class, 'import'])->name('admin.coverage.import');
    Route::get('/coverage/template', [CoverageRequestController::class, 'downloadTemplate'])->name('admin.coverage.template');
    Route::get('/coverage', [CoverageRequestController::class, 'index'])->name('admin.coverage.index');
    Route::post('/coverage', [CoverageRequestController::class, 'store'])->name('admin.coverage.store');
    Route::put('/coverage/{coverageRequest}', [CoverageRequestController::class, 'update'])->name('admin.coverage.update');
    Route::delete('/coverage/{coverageRequest}', [CoverageRequestController::class, 'destroy'])->name('admin.coverage.destroy');

    // Purchase Orders
    Route::get('/purchase-orders', [AdminPurchaseOrderController::class, 'index'])->name('admin.purchase-orders.index');
    Route::get('/purchase-orders/export', [AdminPurchaseOrderController::class, 'export'])->name('admin.purchase-orders.export');
    Route::post('/purchase-orders/import', [AdminPurchaseOrderController::class, 'import'])->name('admin.purchase-orders.import');
    Route::get('/purchase-orders/template', [AdminPurchaseOrderController::class, 'downloadTemplate'])->name('admin.purchase-orders.template');
    Route::put('/purchase-orders/{purchaseOrder}', [AdminPurchaseOrderController::class, 'update'])->name('admin.purchase-orders.update');
    Route::delete('/purchase-orders/{purchaseOrder}', [AdminPurchaseOrderController::class, 'destroy'])->name('admin.purchase-orders.destroy');
    Route::get('/purchase-orders/{purchaseOrder}/pdf', [AdminPurchaseOrderController::class, 'viewPdf'])->name('admin.purchase-orders.pdf');

    // Data Area
    Route::get('/area/export', [AreaController::class, 'export'])->name('admin.area.export');
    Route::post('/area/import', [AreaController::class, 'import'])->name('admin.area.import');
    Route::get('/area/template', [AreaController::class, 'template'])->name('admin.area.template');
    Route::get('/area', [AreaController::class, 'index'])->name('admin.area.index');
    Route::post('/area', [AreaController::class, 'store'])->name('admin.area.store');
    Route::put('/area/{area}', [AreaController::class, 'update'])->name('admin.area.update');
    Route::delete('/area/{area}', [AreaController::class, 'destroy'])->name('admin.area.destroy');

    // Data Order
    Route::get('/orders/export', [OrderController::class, 'export'])->name('admin.orders.export');
    Route::post('/orders/import', [OrderController::class, 'import'])->name('admin.orders.import');
    Route::get('/orders/template', [OrderController::class, 'template'])->name('admin.orders.template');
    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('admin.orders.store');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('admin.orders.update');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');
    Route::delete('/orders-clear', [OrderController::class, 'clearAll'])->name('admin.orders.clear');

    // Reporting
    Route::get('/reporting', [ReportingController::class, 'index'])->name('admin.reporting.index');
});

// ISP Routes
Route::prefix('isp')->middleware(['auth', 'isp'])->group(function () {
    Route::get('/dashboard', [IspDashboardController::class, 'index'])->name('isp.dashboard');
    Route::get('/coverage/create', [IspCoverageController::class, 'create'])->name('isp.coverage.create');
    Route::post('/coverage', [IspCoverageController::class, 'store'])->name('isp.coverage.store');
    Route::get('/coverage/export', [IspDashboardController::class, 'export'])->name('isp.coverage.export');
    Route::post('/coverage/import', [IspDashboardController::class, 'import'])->name('isp.coverage.import');
    Route::get('/coverage/template', [IspDashboardController::class, 'downloadTemplate'])->name('isp.coverage.template');

    // Purchase Orders
    Route::get('/purchase-orders', [IspPurchaseOrderController::class, 'index'])->name('isp.purchase-orders.index');
    Route::get('/purchase-orders/export', [IspPurchaseOrderController::class, 'export'])->name('isp.purchase-orders.export');
    Route::post('/purchase-orders', [IspPurchaseOrderController::class, 'store'])->name('isp.purchase-orders.store');
    Route::put('/purchase-orders/{purchaseOrder}', [IspPurchaseOrderController::class, 'update'])->name('isp.purchase-orders.update');
    Route::delete('/purchase-orders/{purchaseOrder}', [IspPurchaseOrderController::class, 'destroy'])->name('isp.purchase-orders.destroy');
    Route::get('/purchase-orders/{purchaseOrder}/pdf', [IspPurchaseOrderController::class, 'viewPdf'])->name('isp.purchase-orders.pdf');
});

// Profile Routes (Any logged in user)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/password', [ProfileController::class, 'showPasswordForm'])->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

// AI Utilities
Route::middleware(['auth'])->group(function () {
    Route::post('/ai/extract-po', [AiController::class, 'extractPo']);
    Route::post('/ai/chat', [AiController::class, 'chat'])->name('ai.chat');
    Route::get('/ai/chat/history', [AiController::class, 'getChatHistory'])->name('ai.chat.history');
    Route::delete('/ai/chat/clear', [AiController::class, 'clearChatHistory'])->name('ai.chat.clear');
});
