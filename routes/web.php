<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| هنا تعريف المسارات الخاصة بالموقع
|
*/

// الصفحة الرئيسية → إعادة توجيه إلى الأجهزة
Route::get('/', function () {
    return redirect()->route('devices.index');
});

// تعريف Route للوحة التحكم (مطلوب لبـ Breeze)
Route::get('/dashboard', function () {
    return redirect()->route('devices.index'); // dashboard = devices
})->middleware(['auth'])->name('dashboard');


// مجموعة للمستخدمين المسجلين فقط
Route::middleware('auth')->group(function () {

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
});

// Breeze authentication routes
require __DIR__.'/auth.php';
