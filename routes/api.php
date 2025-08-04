<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\JournalController;
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Journal routes here
    Route::get('/journals',        [JournalController::class, 'index']);
    Route::post('/journals',       [JournalController::class, 'store']);
    Route::get('/journals/{id}',   [JournalController::class, 'show']);
    Route::put('/journals/{id}',   [JournalController::class, 'update']);
    Route::delete('/journals/{id}',[JournalController::class, 'destroy']);
});

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
