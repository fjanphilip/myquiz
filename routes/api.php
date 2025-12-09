<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SetController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CardController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function () {
        return auth()->user();
    });
    Route::get('/study-sets', [SetController::class, 'index'])->name('sets');
    Route::post('/study-sets', [SetController::class, 'store'])->name('sets.store');
    Route::get('/study-sets/{id}', [SetController::class, 'show'])->name('sets.show');
    Route::put('/study-sets/{id}', [SetController::class, 'update'])->name('sets.update');
    Route::patch('/study-sets/{id}', [SetController::class, 'update'])->name('sets.update');
    Route::delete('/study-sets/{id}', [SetController::class, 'destroy'])->name('sets.destroy');
    Route::apiResource('cards', \App\Http\Controllers\Api\CardController::class);
});
