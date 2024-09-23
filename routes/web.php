<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\InvoiceController;
use App\Models\Kategori;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

Route::get('/', function(){
    return view('layout.base');
});

Route::get('/dashboard', function () {
    return view('layout.base');
})->name('dashboard');

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
});

Route::resource('roles', RoleController::class);
Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments');
Route::get('/patients', [PatientController::class, 'index'])->name('patients');
Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices');

Route::fallback(function () {
    return redirect('/');
});