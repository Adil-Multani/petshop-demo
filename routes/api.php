<?php

use App\Http\Controllers\Api\v1\AdminController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Middleware\AdminCheckMiddleware;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Middleware\UserCheckMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    // Admin routes
    Route::post('admin/login', [AdminController::class, 'login']);

    Route::middleware([JwtMiddleware::class, AdminCheckMiddleware::class])->prefix('admin')->group(function () {
        Route::get('user-listing', [AdminController::class, 'list']);
        Route::get('logout', [AdminController::class, 'logout']);
        Route::post('create', [AdminController::class, 'create']);
        Route::post('user-edit/{uuid}', [AdminController::class, 'edit']);
        Route::delete('user-delete/{uuid}', [AdminController::class, 'delete']);
    });

    // User routes
    Route::post('user/login', [UserController::class, 'login']);
    Route::post('user/forgot-password', [UserController::class, 'forgotPassword']);
    Route::post('user/reset-password-token', [UserController::class, 'resetPasswordToken']);

    Route::middleware([JwtMiddleware::class, UserCheckMiddleware::class])->prefix('user')->group(function () {
        Route::get('', [UserController::class, 'userDetails']);
        Route::delete('', [UserController::class, 'delete']);
        Route::post('create', [UserController::class, 'create']);
        Route::post('edit', [UserController::class, 'edit'])->name('edit');
        Route::get('logout', [UserController::class, 'logout']);
    });
});

