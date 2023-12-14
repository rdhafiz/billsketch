<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\PayeesController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\RecurringInvoicesController;
use App\Http\Controllers\UserActivityLogsController;
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

// Authentication Route Group
Route::group(['prefix' => 'auth'], function () {
    // User Login: Handles user login through a POST request to '/auth/login'. Invokes 'login' method in 'AuthController'.
    Route::post('login', [AuthController::class, 'login']);
    // Social Login: Handles social login through a POST request to '/auth/social/login'. Invokes 'socialLogin' method in 'AuthController'.
    Route::post('social/login', [AuthController::class, 'socialLogin']);
    // User Registration: Handles user registration through a POST request to '/auth/registration'. Invokes 'registration' method in 'AuthController'.
    Route::post('registration', [AuthController::class, 'registration']);
    // Verify Account: Handles account verification through a POST request to '/auth/verify/account'. Invokes 'verifyAccount' method in 'AuthController'.
    Route::post('verify/account', [AuthController::class, 'verifyAccount']);
    // Forgot Password: Handles forgot password requests through a POST request to '/auth/forgot/password'. Invokes 'forgotPassword' method in 'AuthController'.
    Route::post('forgot/password', [AuthController::class, 'forgotPassword']);
    // Reset Password: Handles password reset through a POST request to '/auth/reset/password'. Invokes 'resetPassword' method in 'AuthController'.
    Route::post('reset/password', [AuthController::class, 'resetPassword']);
});

// Profile Route Group with 'auth:api' Middleware
Route::group(['prefix' => 'profile', 'middleware' => ['auth:api']], function () {
    // Logout: Handles user logout through a GET request to '/profile/logout'. Invokes 'logout' method in 'AuthController'.
    Route::get('logout', [AuthController::class, 'logout']);
    // Get Profile: Retrieves user profile information through a GET request to '/profile/get'. Invokes 'get' method in 'ProfileController'.
    Route::get('get', [ProfileController::class, 'get']);
    // Update Profile: Handles user profile updates through a POST request to '/profile/update'. Invokes 'update' method in 'ProfileController'.
    Route::post('update', [ProfileController::class, 'update']);
    // Update Password: Handles password updates through a POST request to '/profile/update/password'. Invokes 'updatePassword' method in 'ProfileController'.
    Route::post('update/password', [ProfileController::class, 'updatePassword']);
});

// Client Route Group with 'auth:api' Middleware
Route::group(['prefix' => 'client', 'middleware' => ['auth:api']], function () {
    // List Clients: Retrieves a list of clients through a POST request to '/client/list'. Invokes 'index' method in 'ClientsController'.
    Route::post('list', [ClientsController::class, 'index']);
    // Save Client: Handles the creation of a new client through a POST request to '/client/save'. Invokes 'store' method in 'ClientsController'.
    Route::post('save', [ClientsController::class, 'store']);
    // Update Client: Handles updates to an existing client through a POST request to '/client/update'. Invokes 'update' method in 'ClientsController'.
    Route::post('update', [ClientsController::class, 'update']);
    // Single Client: Retrieves details of a single client through a POST request to '/client/single'. Invokes 'show' method in 'ClientsController'.
    Route::post('single', [ClientsController::class, 'show']);
    // Delete Client: Handles the deletion of a client through a POST request to '/client/delete'. Invokes 'destroy' method in 'ClientsController'.
    Route::post('delete', [ClientsController::class, 'destroy']);
    // Update Client Status: Handles archiving or restoring a client through a POST request to '/client/update/status'. Invokes 'archiveOrRestore' method in 'ClientsController'.
    Route::post('update/status', [ClientsController::class, 'archiveOrRestore']);
});




Route::group(['prefix' => 'payee', 'middleware' => ['auth:api']], function () {
    Route::post('save', [PayeesController::class, 'save']);
    Route::post('update', [PayeesController::class, 'update']);
    Route::post('single', [PayeesController::class, 'single']);
    Route::post('delete', [PayeesController::class, 'delete']);
    Route::post('list', [PayeesController::class, 'list']);
    Route::post('update/status', [PayeesController::class, 'archiveOrRestore']);
});
Route::group(['prefix' => 'category', 'middleware' => ['auth:api']], function () {
    Route::post('save', [CategoriesController::class, 'save']);
    Route::post('update', [CategoriesController::class, 'update']);
    Route::post('single', [CategoriesController::class, 'single']);
    Route::post('delete', [CategoriesController::class, 'delete']);
    Route::post('list', [CategoriesController::class, 'list']);
    Route::post('update/status', [CategoriesController::class, 'archiveOrRestore']);
});
Route::group(['prefix' => 'recurring_invoice', 'middleware' => ['auth:api']], function () {
    Route::get('get/frequency', [RecurringInvoicesController::class, 'getRecurringValue']);
    Route::post('save', [RecurringInvoicesController::class, 'save']);
    Route::post('update', [RecurringInvoicesController::class, 'update']);
    Route::post('single', [RecurringInvoicesController::class, 'single']);
    Route::post('delete', [RecurringInvoicesController::class, 'delete']);
    Route::post('list', [RecurringInvoicesController::class, 'list']);
});
Route::group(['prefix' => 'invoice', 'middleware' => ['auth:api']], function () {
    Route::post('save', [InvoicesController::class, 'save']);
    Route::post('update', [InvoicesController::class, 'update']);
    Route::post('single', [InvoicesController::class, 'single']);
    Route::post('delete', [InvoicesController::class, 'delete']);
    Route::post('list', [InvoicesController::class, 'list']);
    Route::post('update/activity', [InvoicesController::class, 'archiveOrRestore']);
    Route::get('get/status', [InvoicesController::class, 'getStatus']);
    Route::post('get/number', [InvoicesController::class, 'getLatestNumber']);
    Route::post('share', [InvoicesController::class, 'share']);
    Route::post('generate/qrcode', [InvoicesController::class, 'generateQRCode']);
    Route::post('update/status', [InvoicesController::class, 'updateStatus']);
    Route::get('dashboard/count', [InvoicesController::class, 'dashboardCount']);
    Route::post('dashboard/chart/month', [InvoicesController::class, 'dashboardChartByMonth']);
    Route::post('dashboard/chart/status', [InvoicesController::class, 'dashboardChartByStatus']);
    Route::post('dashboard/chart/category', [InvoicesController::class, 'dashboardChartByCategory']);
   /* Route::group(['prefix' => 'item', 'middleware' => ['auth:api']], function () {
        Route::post('save', [InvoiceItemsController::class, 'save']);
        Route::post('update', [InvoiceItemsController::class, 'update']);
        Route::post('single', [InvoiceItemsController::class, 'single']);
        Route::post('delete', [InvoiceItemsController::class, 'delete']);
        Route::post('list', [InvoiceItemsController::class, 'list']);
    });*/
});
Route::group(['prefix' => 'user/log', 'middleware' => ['auth:api']], function () {
    Route::post('list', [UserActivityLogsController::class, 'list']);
    Route::get('get/type', [UserActivityLogsController::class, 'getLogType']);
});

Route::group(['prefix' => 'invoice'], function () {
    Route::post('download', [InvoicesController::class, 'download']);
});
Route::group(['prefix' => 'invoice/public'], function () {
    Route::post('view', [InvoicesController::class, 'viewInvoice']);
});
