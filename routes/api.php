<?php

use App\Http\Controllers\VehiclesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TypesController;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

const VEHICLEID = '/vehicles/{id}';
const VEHICLE = '/vehicles';
const TYPE_NAME = 'types/{name}';
const TYPES = '/types';

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

/* Public routes */

// Auth routes
Route::post('/register', [AuthController::class, 'register']);

// Vehicle routes
Route::get(VEHICLE, [VehiclesController::class, 'index']);
Route::get(VEHICLEID, [VehiclesController::class, 'show']);
Route::get('vehicles/search/{license_plate}', [VehiclesController::class, 'search']);
// Parking user type routes
Route::get(TYPES, [TypesController::class, 'index']);
Route::get('/types/search/{type_name}', [TypesController::class, 'search']);

/* Protected routes */

Route::group(['middleware' => ['auth:sanctum']], function() {
    // Vehicle routes
    Route::post(VEHICLE.'/{type}', [VehiclesController::class, 'store']);
    Route::put(VEHICLEID, [VehiclesController::class, 'update']);
    Route::delete(VEHICLEID, [VehiclesController::class, 'destroy']);

    // Update cumulative time for residents
    Route::get('update/{id}/{time}', [VehiclesController::class, 'addTime'])->name('toResidentsUpdate');
    
    // Reset cumulative time count
    Route::put('/vehicles/reset/all', [VehiclesController::class, 'resetCounter']);

    // Parking user type routes
    Route::post(TYPES, [TypesController::class, 'store']);
    Route::put(TYPE_NAME, [TypesController::class, 'update']);
    Route::delete(TYPE_NAME, [TypesController::class, 'destroy']);

    // Parking access route
    Route::post('/access/check_in', [AccessController::class, 'checkIn']);
    Route::put('/access/check_out/{id}', [AccessController::class, 'checkOut']);
    Route::get('/report', [ReportController::class, 'report']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
