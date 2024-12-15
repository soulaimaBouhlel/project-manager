<?php

use App\Http\Controllers\ProjetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\UserController;
use App\Http\Controllers\EquipementController;

Route::post('projects', [ProjetController::class, 'store']);
Route::post('equipments', [EquipementController::class, 'store']);
Route::post('/users', [UserController::class, 'store']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
