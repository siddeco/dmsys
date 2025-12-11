<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PmPlanController;
use App\Http\Controllers\PmRecordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BreakdownController;
use App\Http\Controllers\SparePartController;

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

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('users', \App\Http\Controllers\UserController::class);    

    Route::resource('projects', ProjectController::class);
    

    /*
    |--------------------------------------------------------------------------
    | Devices Module
    |--------------------------------------------------------------------------
    */

    Route::get('/devices', [DeviceController::class, 'index'])
        ->name('devices.index');

    Route::get('/devices/create', [DeviceController::class, 'create'])
        ->name('devices.create');

    Route::post('/devices/store', [DeviceController::class, 'store'])
        ->name('devices.store');

    Route::get('/devices/{id}/edit', [DeviceController::class, 'edit'])
    ->name('devices.edit');

    Route::put('/devices/{id}', [DeviceController::class, 'update'])
    ->name('devices.update');
    


    /*
    |--------------------------------------------------------------------------
    | Preventive Maintenance (PM) Plans Routes
    |--------------------------------------------------------------------------
    */

    Route::get('/pm-plans', [PmPlanController::class, 'index'])
        ->name('pm.plans.index');

    Route::get('/pm-plans/create', [PmPlanController::class, 'create'])
        ->name('pm.plans.create');

    Route::post('/pm-plans/store', [PmPlanController::class, 'store'])
        ->name('pm.plans.store');

    Route::get('/pm-plans/{id}', [PmPlanController::class, 'show'])
        ->name('pm.plans.show');

    /*
    |--------------------------------------------------------------------------
    | Preventive Maintenance (PM) Records Routes
    |--------------------------------------------------------------------------
    */

    // عرض تقارير خطة واحدة
    Route::get('/pm-plans/{plan_id}/records', [PmRecordController::class, 'index'])
        ->name('pm.records.index');

    // إنشاء تقرير صيانة
    Route::get('/pm-plans/{plan_id}/records/create', [PmRecordController::class, 'create'])
        ->name('pm.records.create');

    // حفظ تقرير الصيانة
    Route::post('/pm-plans/{plan_id}/records/store', [PmRecordController::class, 'store'])
        ->name('pm.records.store');

    /*
    |--------------------------------------------------------------------------
    | User Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');



    // ==============================
// Breakdown Module Routes
// ==============================

Route::get('/breakdowns', [BreakdownController::class, 'index'])->name('breakdowns.index');

Route::get('/breakdowns/create', [BreakdownController::class, 'create'])->name('breakdowns.create');

Route::post('/breakdowns/store', [BreakdownController::class, 'store'])->name('breakdowns.store');

Route::get('/breakdowns/{id}', [BreakdownController::class, 'show'])->name('breakdowns.show');


// Spare Parts Module
Route::middleware('auth')->group(function () {

    Route::get('/spare-parts', [SparePartController::class, 'index'])
        ->name('spare_parts.index');

    Route::get('/spare-parts/create', [SparePartController::class, 'create'])
        ->name('spare_parts.create');

    Route::post('/spare-parts/store', [SparePartController::class, 'store'])
        ->name('spare_parts.store');

    Route::get('/spare-parts/{id}/edit', [SparePartController::class, 'edit'])
        ->name('spare_parts.edit');

    Route::post('/spare-parts/{id}/update', [SparePartController::class, 'update'])
        ->name('spare_parts.update');

    Route::delete('/spare-parts/{id}', [SparePartController::class, 'destroy'])
        ->name('spare_parts.delete');
});

    
});

// Breeze authentication routes
require __DIR__.'/auth.php';
