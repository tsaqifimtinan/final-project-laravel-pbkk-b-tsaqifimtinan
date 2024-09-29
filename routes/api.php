<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

Route::get('/doctors', [DoctorController::class, 'index']);
Route::post('/doctors', [DoctorController::class, 'store']);
Route::put('/doctors/{doctors}', [DoctorController::class, 'update']);
Route::delete('/doctors/{doctors}', [DoctorController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user-role', [UserController::class, 'getUserRole']);