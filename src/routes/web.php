<?php

use Idev\EasyAdmin\app\Http\Controllers\RoleController;
use Idev\EasyAdmin\app\Http\Controllers\UserController;
use Idev\EasyAdmin\app\Http\Controllers\AuthController;
use Idev\EasyAdmin\app\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'authenticate'])->middleware('web');
Route::get('/', [AuthController::class, 'login'])->name('login')->middleware('web');
Route::get('cek', [AuthController::class, 'cek']);

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::resource('role', RoleController::class);
    Route::get('role-api', [RoleController::class, 'indexApi'])->name('role.listapi');
    Route::get('role-export-pdf-default', [RoleController::class, 'exportPdf'])->name('role.export-pdf-default');
    Route::get('role-export-excel-default', [RoleController::class, 'exportExcel'])->name('role.export-excel-default');
    Route::post('role-import-excel-default', [RoleController::class, 'importExcel'])->name('role.import-excel-default');

    Route::resource('user', UserController::class);
    Route::get('user-api', [UserController::class, 'indexApi'])->name('user.listapi');
    Route::get('user-export-pdf-default', [UserController::class, 'exportPdf'])->name('user.export-pdf-default');
    Route::get('user-export-excel-default', [UserController::class, 'exportExcel'])->name('user.export-excel-default');
    Route::post('user-import-excel-default', [UserController::class, 'importExcel'])->name('user.import-excel-default');

    Route::get('logout', [AuthController::class, 'logout'])->name("logout");
  
    Route::get('my-account', [UserController::class, 'profile']);
    Route::post('update-profile', [UserController::class, 'updateProfile']);
});
