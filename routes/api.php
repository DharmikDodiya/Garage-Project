<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\StateController;
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

Route::controller(CountryController::class)->prefix('country')->group(function(){
    Route::post('create','create');
    Route::get('list','list');
    Route::patch('update/{id}','update');
    Route::delete('delete/{id}','delete');
    Route::get('get/{id}','get');
});

Route::controller(StateController::class)->prefix('state')->group(function(){
    Route::post('create','create');
    Route::get('list','list');
    Route::patch('update/{id}','update');
    Route::delete('delete/{id}','delete');
    Route::get('get/{id}','get');
});

Route::controller(CityController::class)->prefix('city')->group(function(){
    Route::post('create','create');
    Route::get('list','list');
    Route::patch('update/{id}','update');
    Route::delete('delete/{id}','delete');
    Route::get('get/{id}','get');
});