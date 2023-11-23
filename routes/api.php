<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
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
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('social/login', [AuthController::class, 'socialLogin']);
    Route::post('registration', [AuthController::class, 'registration']);
    Route::post('verify/account', [AuthController::class, 'verifyAccount']);
    Route::post('forgot/password', [AuthController::class, 'forgotPassword']);
    Route::post('reset/password', [AuthController::class, 'resetPassword']);
});
Route::group(['prefix' => 'auth', 'middleware' => ['auth:api']], function () {
    Route::get('/logout', [AuthController::class, 'logout']);
});
Route::group(['prefix' => 'profile', 'middleware' => ['auth:api']], function () {
    Route::get('get', [ProfileController::class, 'get']);
    Route::post('update', [ProfileController::class, 'update']);
    Route::post('update/password', [ProfileController::class, 'updatePassword']);

});


