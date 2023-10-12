<?php

use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\DishController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/restaurant', [RestaurantController::class, 'index']);
Route::get('/restaurant/{rid}', [RestaurantController::class, 'show']);
Route::post('/restaurant', [RestaurantController::class, 'store']);
Route::put('/restaurant/{rid}', [RestaurantController::class, 'update']);
Route::delete('/restaurant/{rid}', [RestaurantController::class, 'destroy']);

Route::get('/restaurant/{rid}/menu', [MenuController::class, 'index']);
Route::get('/restaurant/{rid}/menu/{mid}', [MenuController::class, 'show']);
Route::post('/restaurant/{rid}/menu', [MenuController::class, 'store']);
Route::put('/restaurant/{rid}/menu/{mid}', [MenuController::class, 'update']);
Route::delete('/restaurant/{rid}/menu/{mid}', [MenuController::class, 'destroy']);

Route::get('/restaurant/{rid}/menu/{mid}/dish', [DishController::class, 'index']);
Route::get('/restaurant/{rid}/menu/{mid}/dish/{did}', [DishController::class, 'show']);
Route::post('/restaurant/{rid}/menu/{mid}/dish', [DishController::class, 'store']);
Route::put('/restaurant/{rid}/menu/{mid}/dish/{did}', [DishController::class, 'update']);
Route::delete('/restaurant/{rid}/menu/{mid}/dish/{did}', [DishController::class, 'destroy']);

