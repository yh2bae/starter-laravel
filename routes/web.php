<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'index'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'loginStore'])->name('login.store');
    Route::get('/register', [AuthenticatedSessionController::class, 'register'])->name('register');
    Route::post('/register', [AuthenticatedSessionController::class, 'registerStore'])->name('register.store');
    
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [AuthenticatedSessionController::class, 'logout'])->name('logout');

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/show/{uuid}', [UserController::class, 'show'])->name('show');
        Route::post('/update/{uuid}', [UserController::class, 'update'])->name('update');
        Route::post('/update-password/{uuid}', [UserController::class, 'updatePassword'])->name('updatePassword');
        Route::Delete ('/destroy/{uuid}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/security', [ProfileController::class, 'security'])->name('security');
        Route::post('/update/{uuid}', [ProfileController::class, 'update'])->name('update');
        Route::post('/security/{uuid}', [ProfileController::class, 'updatePasswordProfile'])->name('security.update');
    });

    // Permissions
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::post('/store', [PermissionController::class, 'store'])->name('store');
        Route::get('/show/{id}', [PermissionController::class, 'show'])->name('show');
        Route::post('/update/{id}', [PermissionController::class, 'update'])->name('update');
        Route::Delete ('/destroy/{id}', [PermissionController::class, 'destroy'])->name('destroy');
    });

    // Roles
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RolesController::class, 'index'])->name('index');
        Route::post('/store', [RolesController::class, 'store'])->name('store');
        Route::get('/show/{id}', [RolesController::class, 'show'])->name('show');
        Route::put('/update/{id}', [RolesController::class, 'update'])->name('update');
    });
    
    
    
});
