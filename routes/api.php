<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoiceItemsController;
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
Route::group(['prefix' => 'profile', 'middleware' => ['auth:api']], function () {
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('get', [ProfileController::class, 'get']);
    Route::post('update', [ProfileController::class, 'update']);
    Route::post('update/password', [ProfileController::class, 'updatePassword']);
});
Route::group(['prefix' => 'client', 'middleware' => ['auth:api']], function () {
    Route::post('save', [ClientsController::class, 'save']);
    Route::post('update', [ClientsController::class, 'update']);
    Route::post('single', [ClientsController::class, 'single']);
    Route::post('delete', [ClientsController::class, 'delete']);
    Route::post('list', [ClientsController::class, 'list']);
    Route::post('update/status', [ClientsController::class, 'archiveOrRestore']);
});
Route::group(['prefix' => 'employee', 'middleware' => ['auth:api']], function () {
    Route::post('save', [EmployeesController::class, 'save']);
    Route::post('update', [EmployeesController::class, 'update']);
    Route::post('single', [EmployeesController::class, 'single']);
    Route::post('delete', [EmployeesController::class, 'delete']);
    Route::post('list', [EmployeesController::class, 'list']);
    Route::post('update/status', [EmployeesController::class, 'archiveOrRestore']);
});
Route::group(['prefix' => 'category', 'middleware' => ['auth:api']], function () {
    Route::post('save', [CategoriesController::class, 'save']);
    Route::post('update', [CategoriesController::class, 'update']);
    Route::post('single', [CategoriesController::class, 'single']);
    Route::post('delete', [CategoriesController::class, 'delete']);
    Route::post('list', [CategoriesController::class, 'list']);
    Route::post('update/status', [CategoriesController::class, 'archiveOrRestore']);
});
Route::group(['prefix' => 'invoice', 'middleware' => ['auth:api']], function () {
    Route::post('save', [InvoicesController::class, 'save']);
    Route::post('update', [InvoicesController::class, 'update']);
    Route::post('single', [InvoicesController::class, 'single']);
    Route::post('delete', [InvoicesController::class, 'delete']);
    Route::post('list', [InvoicesController::class, 'list']);
    Route::post('update/activity', [InvoicesController::class, 'archiveOrRestore']);
    Route::get('get/status', [InvoicesController::class, 'getStatus']);
    Route::get('get/recurring', [InvoicesController::class, 'getRecurringValue']);
    Route::post('get/number', [InvoicesController::class, 'getLatestNumber']);
    Route::post('share', [InvoicesController::class, 'share']);
    Route::post('generate/qrcode', [InvoicesController::class, 'generateQRCode']);
    Route::group(['prefix' => 'item', 'middleware' => ['auth:api']], function () {
        Route::post('save', [InvoiceItemsController::class, 'save']);
        Route::post('update', [InvoiceItemsController::class, 'update']);
        Route::post('single', [InvoiceItemsController::class, 'single']);
        Route::post('delete', [InvoiceItemsController::class, 'delete']);
        Route::post('list', [InvoiceItemsController::class, 'list']);
    });
});
Route::group(['prefix' => 'invoice/public'], function () {
    Route::post('view', [InvoicesController::class, 'viewInvoice']);
});
