<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Kategori;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

// Route::get('/', function(){
//     return view('auth.home');
// });

Route::get('/dashboard', function () {
    return view('layout.base');
})->name('dashboard');

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
});

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::get('/doctors', function () {
    return Inertia::render('Doctor/Manage');
})->name('doctors.index');

Route::resource('roles', RoleController::class);
Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments');
Route::get('/patients', [PatientController::class, 'index'])->name('patients');
Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices');

// Route::get('/register', [RegisterController::class,'show'])->name('register.create');
// Route::post('/register', [RegisterController::class,'register'])->name('register.show');
// Route::get('/login', [LoginController::class,'show'])->name('login');
// Route::post('/login', [LoginController::class,'login'])->name('login.create');
// Route::post('/logout', [LoginController::class,'logout'])->name('login.destroy');

Route::get('/doctor', [DoctorController::class,'index'])->name('doctor.index');

Route::fallback(function () {
    return redirect('/');
});