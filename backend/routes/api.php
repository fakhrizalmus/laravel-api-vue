<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TugasController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group(['prefix' => 'tugas'], function ($router) {
    Route::get('', [TugasController::class, 'index'])->middleware('auth:api');
    Route::post('create', [TugasController::class, 'store'])->middleware('auth:api');
    Route::put('edit/{id}', [TugasController::class, 'update'])->middleware('auth:api');
    Route::delete('delete/{id}', [TugasController::class, 'destroy'])->middleware('auth:api');
});
