<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PmPlanController;
use App\Http\Controllers\PmRecordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BreakdownController;
use App\Http\Controllers\ProjectDocumentController;
use App\Http\Controllers\SparePartTransactionController;
use App\Http\Controllers\SparePartController;
use App\Http\Controllers\Reports\SparePartReportController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| هنا تعريف المسارات الخاصة بالموقع
|
*/

// الصفحة الرئيسية → إعادة توجيه إلى لوحة التحكم
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// تغيير اللغة
Route::get('/lang/{lang}', function ($lang) {
    session(['locale' => $lang]);
    return back();
})->name('lang.switch');


// جميع المسارات التالية للمستخدمين المسجّلين فقط
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Users
    Route::resource('users', \App\Http\Controllers\UserController::class);



    // Projects
    Route::resource('projects', ProjectController::class);

    // ===============================
// Project Documents
// ===============================
    Route::prefix('projects/{project}')->group(function () {

        Route::get('documents', [ProjectDocumentController::class, 'index'])
            ->name('projects.documents.index');

        Route::post('documents', [ProjectDocumentController::class, 'store'])
            ->name('projects.documents.store');

        Route::get('documents/{document}/download', [ProjectDocumentController::class, 'download'])
            ->name('projects.documents.download');

        Route::patch('documents/{document}/archive', [ProjectDocumentController::class, 'archive'])
            ->name('projects.documents.archive');

        Route::patch('documents/{document}/restore', [ProjectDocumentController::class, 'restore'])
            ->name('projects.documents.restore');

        Route::delete('documents/{document}', [ProjectDocumentController::class, 'destroy'])
            ->name('projects.documents.destroy');

    });






    // Device Archive

    Route::get('/devices/archived', [DeviceController::class, 'archived'])
        ->name('devices.archived');

    Route::patch('/devices/{device}/archive', [DeviceController::class, 'archive'])
        ->name('devices.archive');

    Route::patch('/devices/{device}/restore', [DeviceController::class, 'restore'])
        ->name('devices.restore');


    // Devices
    Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
    Route::get('/devices/create', [DeviceController::class, 'create'])->name('devices.create');
    Route::post('/devices/store', [DeviceController::class, 'store'])->name('devices.store');
    Route::get('/devices/{id}/edit', [DeviceController::class, 'edit'])->name('devices.edit');
    Route::put('/devices/{id}', [DeviceController::class, 'update'])->name('devices.update');






    // PM Plans
    Route::get('/pm-plans', [PmPlanController::class, 'index'])->name('pm.plans.index');
    Route::get('/pm-plans/create', [PmPlanController::class, 'create'])->name('pm.plans.create');
    Route::post('/pm-plans/store', [PmPlanController::class, 'store'])->name('pm.plans.store');
    Route::get('/pm-plans/{id}', [PmPlanController::class, 'show'])->name('pm.plans.show');

    Route::post('/pm-plans/{plan}/assign', [PmPlanController::class, 'assign'])
        ->name('pm.plans.assign');

    Route::post('/pm-plans/{plan}/start', [PmPlanController::class, 'start'])
        ->name('pm.plans.start');

    Route::post('/pm-plans/{plan}/complete', [PmPlanController::class, 'complete'])
        ->name('pm.plans.complete');

    Route::post('pm/plans/bulk', [PmPlanController::class, 'bulk'])
        ->name('pm.plans.bulk')
        ->middleware('can:manage pm');


    Route::prefix('pm-plans')->name('pm.plans.')->group(function () {
        Route::get('{plan}/edit', [PmPlanController::class, 'edit'])->name('edit');
        Route::put('{plan}', [PmPlanController::class, 'update'])->name('update');

    });



    // PM Records
    Route::middleware(['auth'])->group(function () {

        Route::get('/pm/records', [PmRecordController::class, 'index'])
            ->name('pm.records.index')
            ->middleware('can:manage pm');

    });

    Route::get(
        '/pm/records/{record}',
        [PmRecordController::class, 'show']
    )->name('pm.records.show')
        ->middleware(['auth', 'can:manage pm']);


    Route::get('/pm-plans/{plan_id}/records/create', [PmRecordController::class, 'create'])->name('pm.records.create');
    Route::post('/pm-plans/{plan_id}/records/store', [PmRecordController::class, 'store'])->name('pm.records.store');


    // Breakdowns
    Route::get('/breakdowns', [BreakdownController::class, 'index'])->name('breakdowns.index');
    Route::get('/breakdowns/create', [BreakdownController::class, 'create'])->name('breakdowns.create');
    Route::post('/breakdowns/store', [BreakdownController::class, 'store'])->name('breakdowns.store');
    Route::get('/breakdowns/{id}', [BreakdownController::class, 'show'])->name('breakdowns.show');
    // Workflow actions
    Route::post('/breakdowns/{breakdown}/assign', [BreakdownController::class, 'assign'])
        ->name('breakdowns.assign');

    Route::post('/breakdowns/{breakdown}/start', [BreakdownController::class, 'start'])
        ->name('breakdowns.start');

    Route::post('/breakdowns/{breakdown}/resolve', [BreakdownController::class, 'resolve'])
        ->name('breakdowns.resolve');

    Route::post('/breakdowns/{breakdown}/close', [BreakdownController::class, 'close'])
        ->name('breakdowns.close');

    // routes/web.php
    Route::post(
        '/breakdowns/{breakdown}/spare-parts/issue',
        [BreakdownController::class, 'issueSparePart']
    )->name('breakdowns.issue-part');

    Route::post(
        '/breakdowns/{breakdown}/spare-parts/return',
        [BreakdownController::class, 'returnSparePart']
    )->name('breakdowns.return-part');











    // Spare Parts
    Route::get('/spare-parts', [SparePartController::class, 'index'])->name('spare_parts.index');
    Route::get('/spare-parts/create', [SparePartController::class, 'create'])->name('spare_parts.create');
    Route::post('/spare-parts/store', [SparePartController::class, 'store'])->name('spare_parts.store');
    Route::get('/spare-parts/{id}/edit', [SparePartController::class, 'edit'])->name('spare_parts.edit');
    Route::post('/spare-parts/{id}/update', [SparePartController::class, 'update'])->name('spare_parts.update');
    Route::delete('/spare-parts/{id}', [SparePartController::class, 'destroy'])->name('spare_parts.delete');

    Route::get('/spare-parts/{sparePart}/transactions', [SparePartTransactionController::class, 'index'])
        ->name('spare_parts.transactions.index');

    Route::post('/spare-parts/{sparePart}/transactions/in', [SparePartTransactionController::class, 'storeIn'])
        ->name('spare_parts.transactions.in');

    Route::post('/spare-parts/{sparePart}/transactions/out', [SparePartTransactionController::class, 'storeOut'])
        ->name('spare_parts.transactions.out');

    Route::middleware(['auth'])->group(function () {
        Route::get('/reports/spare-parts', [SparePartReportController::class, 'index'])
            ->name('reports.spare-parts');
    });

    Route::get(
        '/reports/spare-parts/export',
        [SparePartReportController::class, 'export']
    )->name('reports.spare-parts.export');


    Route::get(
        '/reports/spare-parts/export/pdf',
        [SparePartReportController::class, 'exportPdf']
    )->name('reports.spare-parts.export.pdf');





    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// Breeze authentication routes
require __DIR__ . '/auth.php';
