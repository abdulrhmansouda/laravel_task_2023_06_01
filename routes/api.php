<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware'    => ['transaction'],
], function () {
    Route::group([
        'prefix'    => 'customer',
    ], function () {
        Route::post('/', [CustomerController::class, 'store']);
        Route::get('/getCustomerOrderReports', [CustomerController::class, 'getCustomerOrderReports']);
    });
    Route::group([
        'prefix'    => 'order',
    ], function () {
        Route::get('/getOrderReports', [OrderController::class, 'getOrderReports']);
    });
});
