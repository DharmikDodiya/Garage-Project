<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CarServicingJobController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\GarageController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\UserController;
use App\Models\CarServicingJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use function PHPSTORM_META\type;

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



/**
 * UnAuthenicated Routes
 */
Route::controller(AuthController::class)->group(function(){
    Route::post('register','register');
    Route::get('verifyuser/{token}','verifyAccount');
    Route::post('login','login');
    Route::post('forget-password','forgetPassword');
    Route::get('resetPassword','forgetPasswordView');
    Route::post('reset-Password','resetPassword');
});


/**
 * Authenicated Routes
 */
Route::middleware('auth:api')->group(function(){

/**
 * User Authenicated Routes
 */
Route::controller(UserController::class)->group(function(){
    Route::post('change-password','changePassword');
    Route::get('user-profile','userProfile');
    Route::get('logout','logout');
});


/**
 * Country Routes
 */
Route::controller(CountryController::class)->middleware(['type:admin'])->prefix('country')->group(function(){
    Route::post('create','create');
    Route::get('list','list');
    Route::patch('update/{country}','update');
    Route::delete('delete/{id}','delete');
    Route::get('get/{id}','get');
});

/**
 * State Routes
 */
Route::controller(StateController::class)->middleware(['type:admin'])->prefix('state')->group(function(){
    Route::post('create','create');
    Route::get('list','list');
    Route::patch('update/{state}','update')->withoutMiddleware('type');
    Route::delete('delete/{id}','delete');
    Route::get('get/{id}','get');
});

/**
 * City Routes
 */
Route::controller(CityController::class)->middleware(['type:admin'])->prefix('city')->group(function(){
    Route::post('create','create');
    Route::get('list','list');
    Route::patch('update/{city}','update');
    Route::delete('delete/{id}','delete');
    Route::get('get/{id}','get');
});

/**
 * ServiceType Routes
 */
Route::controller(ServiceTypeController::class)->middleware('type:garage owner')->prefix('service-type')->group(function(){
    Route::post('create','create');
    Route::get('list','list');
    Route::patch('update/{servicetype}','update');
    Route::delete('delete/{id}','delete');
    Route::get('get/{id}','get');
});


/**
 * Garage Routes
 */
Route::controller(GarageController::class)->middleware('type:garage owner')->prefix('garage')->group(function(){
    Route::post('search-garage','searchingGarage')->withoutMiddleware('type:garage owner');
    Route::post('create','create');
    Route::post('create-mechanic','createMechanic');
    Route::get('list','list');
    Route::patch('update/{garage}','update');
    Route::delete('delete/{id}','delete');
    Route::get('get/{id}','get');
});


/**
 * Car Route
 */
Route::controller(CarController::class)->middleware('type:customer')->prefix('car')->group(function(){
    Route::post('create','create');
    Route::get('list','list');
    Route::patch('update/{id}','update');
    Route::delete('delete/{id}','delete');
    Route::get('get/{id}','get');
});

/**
 * CarServicingJobs Route
 */
Route::controller(CarServicingJobController::class)->prefix('car-servicing-job')->group(function(){
    Route::post('create','create')->middleware('type:garage owner');
    Route::get('list','list')->middleware('type:mechanic');
    Route::patch('update/{id}','update')->middleware('type:mechanic');
});

});