<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Supervisor\UsersController;
use App\Http\Controllers\Api\Admin\HomeController;
use App\Http\Controllers\Api\Stations\StationSalesController;
use App\Http\Controllers\Api\Supervisor\StationsProductsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

//users routes by supervisor
Route::group(['prefix' => 'stations_users', 'middleware' => ['jwt.verify', 'issupervisor']], function() {

    Route::post('create', [UsersController::class, 'store']);
    Route::post('{id}/update', [UsersController::class, 'update']);
    Route::post('{id}/delete', [UsersController::class, 'destroy']);
    Route::post('{id}/add_product', [StationsProductsController::class, 'store']);
    Route::post('{id}/update_product/{product}', [StationsProductsController::class, 'update']);
    Route::post('{id}/delete_product/{product}', [StationsProductsController::class, 'destroy']);
    
});

//admins routes
Route::group(['prefix' => 'stations', 'middleware' => ['jwt.verify', 'isadmin']], function() {

    Route::get('sales', [HomeController::class, 'index']);

});

Route::group(['prefix' => 'station', 'middleware' => ['jwt.verify', 'isstation']], function() {

    Route::get('sales/', [StationSalesController::class, 'show']);
    Route::post('sales/create', [StationSalesController::class, 'store']);
    Route::post('sales/{id}/update', [StationSalesController::class, 'update']);
    Route::post('sales/{id}/delete', [StationSalesController::class, 'destroy']);

});

// Auth routes
Route::group(['middleware' => 'api'], function() {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('jwt.verify');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('jwt.verify');

});
