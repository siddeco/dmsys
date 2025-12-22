<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    DeviceController,
    ProjectController,
    PmPlanController,
    PmRecordController,
    BreakdownController,
    SparePartController,
    SparePartTransactionController,
    UserController,
    ProfileController
};
use App\Http\Controllers\Reports\{
    SparePartReportController,
    SparePartConsumptionController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// الصفحة الرئيسية
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// تغيير اللغة
Route::get('/lang/{lang}', function ($lang) {
    session(['locale' => $lang]);
    return back();
})->name('lang.switch');

// ==================== AUTHENTICATED ROUTES ====================
Route::middleware('auth')->group(function () {

    // ==================== DASHBOARD ====================
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // ==================== DEVICES ====================
    Route::prefix('devices')->name('devices.')->group(function () {
        // المسارات العامة (بدون معاملات)
        Route::get('/', [DeviceController::class, 'index'])->name('index');
        Route::get('/create', [DeviceController::class, 'create'])->name('create');
        Route::get('/archived', [DeviceController::class, 'archived'])->name('archived');
        Route::post('/', [DeviceController::class, 'store'])->name('store');

        // المسارات التي تتطلب معامل (ID جهاز) مع تحقق أنه رقم
        Route::get('/{device}', [DeviceController::class, 'show'])
            ->whereNumber('device')
            ->name('show');

        Route::get('/{device}/edit', [DeviceController::class, 'edit'])
            ->whereNumber('device')
            ->name('edit');

        Route::put('/{device}', [DeviceController::class, 'update'])
            ->whereNumber('device')
            ->name('update');

        Route::patch('/{device}/archive', [DeviceController::class, 'archive'])
            ->whereNumber('device')
            ->name('archive');

        Route::patch('/{device}/restore', [DeviceController::class, 'restore'])
            ->whereNumber('device')
            ->name('restore');

        // استعادة أجهزة متعددة (إذا أردت)
        Route::post('/bulk-restore', [DeviceController::class, 'bulkRestore'])
            ->name('bulk-restore');
    });


    // ==================== PROJECTS ====================
    Route::prefix('projects')->name('projects.')->group(function () {
        // المسارات العامة
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::get('/create', [ProjectController::class, 'create'])->name('create');
        Route::get('/completed', [ProjectController::class, 'completed'])->name('completed');
        Route::get('/overdue', [ProjectController::class, 'overdue'])->name('overdue');
        Route::post('/', [ProjectController::class, 'store'])->name('store');

        // المسارات التي تتطلب معامل (ID مشروع)
        Route::get('/{project}', [ProjectController::class, 'show'])
            ->whereNumber('project')
            ->name('show');

        Route::get('/{project}/edit', [ProjectController::class, 'edit'])
            ->whereNumber('project')
            ->name('edit');

        Route::put('/{project}', [ProjectController::class, 'update'])
            ->whereNumber('project')
            ->name('update');

        Route::delete('/{project}', [ProjectController::class, 'destroy'])
            ->whereNumber('project')
            ->name('destroy');

        // تحديثات جزئية
        Route::patch('/{project}/status', [ProjectController::class, 'updateStatus'])
            ->whereNumber('project')
            ->name('update-status');

        Route::patch('/{project}/budget', [ProjectController::class, 'updateBudget'])
            ->whereNumber('project')
            ->name('update-budget');

        Route::patch('/{project}/restore', [ProjectController::class, 'restore'])
            ->whereNumber('project')
            ->name('restore');
    });

    // ==================== PM PLANS ====================
    Route::prefix('pm-plans')->name('pm.plans.')->group(function () {
        Route::get('/', [PmPlanController::class, 'index'])->name('index');
        Route::get('/create', [PmPlanController::class, 'create'])->name('create');
        Route::post('/', [PmPlanController::class, 'store'])->name('store');
        Route::get('/{pmPlan}', [PmPlanController::class, 'show'])->name('show');
        Route::get('/{pmPlan}/edit', [PmPlanController::class, 'edit'])->name('edit');
        Route::put('/{pmPlan}', [PmPlanController::class, 'update'])->name('update');
        Route::post('/{pmPlan}/assign', [PmPlanController::class, 'assign'])->name('assign');
        Route::post('/{pmPlan}/start', [PmPlanController::class, 'start'])->name('start');
        Route::post('/{pmPlan}/complete', [PmPlanController::class, 'complete'])->name('complete');
        Route::post('/bulk', [PmPlanController::class, 'bulk'])->name('bulk');
    });

    // ==================== BREAKDOWNS ====================
    Route::prefix('breakdowns')->name('breakdowns.')->group(function () {
        Route::get('/', [BreakdownController::class, 'index'])->name('index');
        Route::get('/create', [BreakdownController::class, 'create'])->name('create');
        Route::post('/', [BreakdownController::class, 'store'])->name('store');
        Route::get('/{breakdown}', [BreakdownController::class, 'show'])->name('show');
        Route::post('/{breakdown}/assign', [BreakdownController::class, 'assign'])->name('assign');
        Route::post('/{breakdown}/start', [BreakdownController::class, 'start'])->name('start');
        Route::post('/{breakdown}/resolve', [BreakdownController::class, 'resolve'])->name('resolve');
        Route::post('/{breakdown}/close', [BreakdownController::class, 'close'])->name('close');
    });

    // ==================== SPARE PARTS ====================
    Route::prefix('spare-parts')->name('spare_parts.')->group(function () {
        Route::get('/', [SparePartController::class, 'index'])->name('index');
        Route::get('/create', [SparePartController::class, 'create'])->name('create');
        Route::post('/', [SparePartController::class, 'store'])->name('store');
        Route::get('/{sparePart}/edit', [SparePartController::class, 'edit'])->name('edit');
        Route::put('/{sparePart}', [SparePartController::class, 'update'])->name('update');
        Route::delete('/{sparePart}', [SparePartController::class, 'destroy'])->name('destroy');

        // معاملات قطع الغيار
        Route::get('/{sparePart}/transactions', [SparePartTransactionController::class, 'index'])
            ->name('transactions.index');
        Route::post('/{sparePart}/transactions/in', [SparePartTransactionController::class, 'storeIn'])
            ->name('transactions.in');
        Route::post('/{sparePart}/transactions/out', [SparePartTransactionController::class, 'storeOut'])
            ->name('transactions.out');
    });

    // ⭐⭐⭐⭐ القسم الصحيح للتقارير ⭐⭐⭐⭐
    // ==================== REPORTS ====================
    Route::prefix('reports')->name('reports.')->group(function () {
        // تقرير قطع الغيار الرئيسي
        Route::get('/spare-parts', [SparePartReportController::class, 'index'])
            ->name('spare-parts'); // المسار: /reports/spare-parts

        // استهلاك قطع الغيار
        Route::get('/spare-parts/consumption', [SparePartConsumptionController::class, 'index'])
            ->name('spare-parts.consumption'); // المسار: /reports/spare-parts/consumption

        // تصدير Excel
        Route::get('/spare-parts/consumption/export/excel', [SparePartReportController::class, 'exportExcel'])
            ->name('spare-parts.consumption.export.excel');

        // تصدير PDF
        Route::get('/spare-parts/consumption/export/pdf', [SparePartReportController::class, 'exportPdf'])
            ->name('spare-parts.consumption.export.pdf');
    });

    // ==================== USERS ====================
    Route::resource('users', UserController::class)->except(['show']);

    // تبديل حالة المستخدم
    Route::post('/users/{user}/toggle', [UserController::class, 'toggleStatus'])
        ->name('users.toggle');

    // ==================== PROFILE ====================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==================== AUTH ROUTES ====================
require __DIR__ . '/auth.php';