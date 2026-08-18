<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuotationController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    | Profile
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    | Dashboard
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    | Inventory - Storekeeper and Admin only
    */
    Route::middleware(['storekeeper'])->group(function () {
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');

        Route::get('/inventory/product/create', [InventoryController::class, 'createProduct'])
            ->name('inventory.product.create');

        Route::post('/inventory/product/store', [InventoryController::class, 'storeProduct'])
            ->name('inventory.product.store');

        Route::get('/inventory/{product}/stock', [InventoryController::class, 'stockForm'])
            ->name('inventory.stock.form');

        Route::post('/inventory/{product}/stock', [InventoryController::class, 'stockMovement'])
            ->name('inventory.stock.process');
    });

    /*
    | Products - Storekeeper and Admin only
    */
    Route::middleware(['storekeeper'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    });


    /*
    | Repair Jobs - Technician and Admin only
    */
    Route::middleware(['technician'])->group(function () {
        Route::resource('jobs', JobController::class);
        Route::get('/jobs/{job}/add-part', [JobController::class, 'addPartForm'])
            ->name('jobs.add-part');
        Route::post('/jobs/{job}/add-part', [JobController::class, 'storePart'])
            ->name('jobs.store-part');
        Route::post('/jobs/{job}/status', [JobController::class, 'updateStatus'])
            ->name('jobs.update-status');
    });

    /*
    | Reports - Role-based access
    */
    Route::middleware(['auth'])->group(function () {
        Route::get('/reports', [ReportsController::class, 'dashboard'])->name('reports.index');
        Route::get('/reports/activity', [ReportsController::class, 'activity'])->name('reports.activity');
        
        // Inventory Reports - Storekeeper and Admin
        Route::middleware(['storekeeper'])->group(function () {
            Route::get('/reports/inventory/email', [ReportsController::class, 'inventoryEmail'])->name('reports.inventory.email');
            Route::post('/reports/inventory/send', [ReportsController::class, 'sendInventoryReport'])->name('reports.inventory.send');
            Route::get('/reports/products/email', [ReportsController::class, 'productsEmail'])->name('reports.products.email');
            Route::post('/reports/products/send', [ReportsController::class, 'sendProductsReport'])->name('reports.products.send');
            Route::get('/reports/warranty-parts', [ReportsController::class, 'warrantyPartsUsage'])
                ->name('reports.warranty-parts');
        });
        
        // Email Reports - Admin only
        Route::middleware(['admin'])->group(function () {
            Route::get('/reports/email', [ReportsController::class, 'email'])->name('reports.email');
            Route::post('/reports/send-to-client', [ReportsController::class, 'sendToClient'])
                ->name('reports.send-to-client');
            Route::post('/reports/send-to-staff', [ReportsController::class, 'sendToStaff'])
                ->name('reports.send-to-staff');
            Route::post('/reports/send-to-managers', [ReportsController::class, 'sendToManagers'])
                ->name('reports.send-to-managers');
        });
        
        // Technician Reports - Technician and Admin
        Route::middleware(['technician'])->group(function () {
            Route::get('/reports/technician/jobs', [ReportsController::class, 'technicianJobs'])->name('reports.technician.jobs');
        });
    });

    /*
    | User Management - Admin only
    */
    Route::middleware(['admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
        Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
        
        // Admin-only user registration
        Route::get('/register-user', [UserController::class, 'create'])->name('admin.register-user');
        Route::post('/register-user', [UserController::class, 'store'])->name('admin.register-user.store');
    });

    // API routes for scheduled reports (could be called by cron jobs)
    Route::get('/api/reports/daily', [ReportsController::class, 'sendDailyReport']);
    Route::get('/api/reports/weekly', [ReportsController::class, 'sendWeeklyReport']);

    /*
    | Quotations - Technician and Admin only (for creating and managing)
    */
    Route::middleware(['technician'])->group(function () {
        Route::get('/quotations', [QuotationController::class, 'index'])->name('quotations.index');
        Route::get('/quotations/create', [QuotationController::class, 'create'])->name('quotations.create');
        Route::get('/quotations/create/{job}', [QuotationController::class, 'createWithJob'])->name('quotations.create.with.job');
        Route::post('/quotations/store', [QuotationController::class, 'store'])->name('quotations.store');
        Route::get('/quotations/{id}', [QuotationController::class, 'show'])->name('quotations.show');
        Route::post('/quotations/{id}/email', [QuotationController::class, 'sendEmail'])->name('quotations.email');
        Route::post('/quotations/{id}/accept', [QuotationController::class, 'acceptQuotation'])->name('quotations.accept');
        Route::post('/quotations/{id}/reject', [QuotationController::class, 'rejectQuotation'])->name('quotations.reject');
        Route::post('/quotations/{id}/send', [QuotationController::class, 'sendQuotation'])->name('quotations.send');
        Route::post('/quotations/{id}/update-prices', [QuotationController::class, 'updatePrices'])->name('quotations.update-prices');
    });
});

/*
|--------------------------------------------------------------------------
| Auth scaffolding
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
