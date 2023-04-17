<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {return inertia('Auth/Login'); });
Route::middleware(['auth'])->group(function () {
    Route::resource('/home', App\Http\Controllers\HomeController::class);
    Route::resource('/staffs', App\Http\Controllers\StaffController::class);
    Route::resource('/schools', App\Http\Controllers\SchoolController::class);

    Route::resource('/scholars', App\Http\Controllers\Scholar\IndexController::class);
    Route::resource('/enrollments', App\Http\Controllers\Scholar\Enrollment\IndexController::class);
    Route::resource('/financial-benefits', App\Http\Controllers\Scholar\FinancialBenefit\IndexController::class);
    Route::post('/grade/store', [App\Http\Controllers\Scholar\Evaluation\IndexController::class, 'store']);
    Route::resource('/insights', App\Http\Controllers\InsightController::class);
    Route::get('/locations', [App\Http\Controllers\InsightController::class, 'location']);

    Route::prefix('excel')->group(function(){
        Route::post('/scholar/import', [App\Http\Controllers\Scholar\ImportController::class, 'index']);
        Route::post('/scholar/store', [App\Http\Controllers\Scholar\ImportController::class, 'store']);
    });
});

require __DIR__.'/auth.php';
require __DIR__.'/lists.php';
require __DIR__.'/installation.php';