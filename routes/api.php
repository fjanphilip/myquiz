<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SetController;
use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function () {
        return auth()->user();
    });
    Route::get('/sets', [SetController::class, 'index'])->name('sets');
    Route::post('/sets', [SetController::class, 'store'])->name('sets.store');
    Route::get('/sets/{id}', [SetController::class, 'show'])->name('sets.show');
    Route::put('/sets/{id}', [SetController::class, 'update'])->name('sets.update');
    Route::patch('/sets/{id}', [SetController::class, 'update'])->name('sets.update');
    Route::delete('/sets/{id}', [SetController::class, 'destroy'])->name('sets.destroy');
});
