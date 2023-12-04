<?php

use Illuminate\Http\Request;
use App\Adapters\SWAPIAdapter;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FilmController;
use App\Http\Controllers\Api\RootController;
use App\Http\Controllers\Api\PeopleController;
use App\Http\Controllers\Api\PlanetController;
use App\Http\Controllers\Api\SpecieController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\StarshipController;

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
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/welcome', function (Request $request) {
    $swapi = new SWAPIAdapter('vehicles');
        
    return $swapi->getResponse();
});
Route::get('/welcome/{id}', function (Request $request) {
    $swapi = new SWAPIAdapter('vehicles');
    
    return $swapi->getResponse();
});
*/

Route::resource('/', RootController::class)->only([
    'index'
]);
Route::resource('/films', FilmController::class)->only([
    'index', 'show'
]);
Route::resource('/people', PeopleController::class)->only([
    'index', 'show'
]);
Route::resource('/planets', PlanetController::class)->only([
    'index', 'show'
]);
Route::resource('/species', SpecieController::class)->only([
    'index', 'show'
]);


Route::resource('/starships', StarshipController::class)->only([
    'index', 'show', 'update'
]);
Route::put('/starships/{id}/increase', [StarshipController::class, 'increase']);
Route::put('/starships/{id}/decrease', [StarshipController::class, 'decrease']);


Route::resource('/vehicles', VehicleController::class)->only([
    'index', 'show', 'update'
]);
Route::put('/vehicles/{id}/increase', [VehicleController::class, 'increase']);
Route::put('/vehicles/{id}/decrease', [VehicleController::class, 'decrease']);
