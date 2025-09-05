<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Cashier\PosController;
use App\Http\Controllers\Cashier\OrderController as CashierOrderController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use Illuminate\Support\Facades\Route;

// Public Routes - Hanya untuk guest
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    
    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        
        // User Management
        Route::resource('/users', UserController::class);
        
        // Product Management
        Route::resource('/products', ProductController::class);
        
        // Category Management
        Route::resource('/categories', CategoryController::class);
        
        // Customer Management
        Route::resource('/customers', CustomerController::class);
        
        // Order Management
        Route::resource('/orders', OrderController::class);
        
        // Reports
        Route::get('/reports/sales', [ReportController::class, 'salesReport'])->name('reports.sales');
        Route::get('/reports/inventory', [ReportController::class, 'inventoryReport'])->name('reports.inventory');
    });
    
    // Cashier Routes
    Route::middleware('role:cashier')->prefix('cashier')->name('cashier.')->group(function () {
        // Dashboard
        Route::get('/dashboard', function () {
            return view('cashier.dashboard');
        })->name('dashboard');

        // POS Interface
        Route::get('/pos', [PosController::class, 'index'])->name('pos');
        Route::post('/pos/process-order', [PosController::class, 'processOrder'])->name('pos.process-order');
        Route::get('/pos/product/{id}', [PosController::class, 'getProduct'])->name('pos.get-product');
        
        // Order Management for Cashier
    Route::resource('/orders', CashierOrderController::class);
    // Custom receipt print route for cashier orders
    Route::get('/orders/{order}/receipt', [CashierOrderController::class, 'printReceipt'])->name('orders.receipt');
    // PDF download for receipt (uses barryvdh/laravel-dompdf if available)
    Route::get('/orders/{order}/receipt/pdf', [CashierOrderController::class, 'downloadPdf'])->name('orders.pdf');
    });
    
    // Manager Routes
    Route::middleware('role:manager')->prefix('manager')->name('manager.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
        
        // Reports
        Route::get('/reports/sales', [ReportController::class, 'salesReport'])->name('reports.sales');
        Route::get('/reports/inventory', [ReportController::class, 'inventoryReport'])->name('reports.inventory');
    // Manager product views (read-only)
    Route::get('/products', [\App\Http\Controllers\Manager\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [\App\Http\Controllers\Manager\ProductController::class, 'show'])->name('products.show');
    Route::get('/products-export', [\App\Http\Controllers\Manager\ProductController::class, 'export'])->name('products.export');
    });
    
    // Common Routes (bisa diakses oleh semua role yang terautentikasi)
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// Fallback Route
Route::fallback(function () {
    return redirect()->route('login');
});

// Midtrans webhook (public) - exclude CSRF and role middleware so external webhook can call it
Route::post('/midtrans/notify', [\App\Http\Controllers\Cashier\PosController::class, 'midtransNotify'])
    ->name('midtrans.notify')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class, \App\Http\Middleware\CheckRole::class]);


    