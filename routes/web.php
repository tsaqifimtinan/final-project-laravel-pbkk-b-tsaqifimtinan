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
use App\Http\Controllers\SwaggerController;

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
    return Inertia::render('Doctor/DoctorDashboard');
})->name('doctors.index');

Route::get('/patients', function () {
    return Inertia::render('Patient/PatientDashboard');
})->name('patients.index');

Route::get('/appointments', function () {
    return Inertia::render('Appointment/Manage');
})->name('appointments.index');

Route::get('/invoices', function () {
    return Inertia::render('Invoice/Manage');
})->name('invoices.index');

Route::get('/medications', function () {
    return Inertia::render('Medication/Manage');
})->name('medications.index');

Route::get('/payments', function () {
    return Inertia::render('Payment/Manage');
})->name('payments.index');

Route::get('/prescriptions', function () {
    return Inertia::render('Prescription/Manage');
})->name('prescriptions.index');

Route::get('/rooms', function () {
    return Inertia::render('Rooms/Manage');
})->name('rooms.index');

Route::get('/treatments', function () {
    return Inertia::render('Treatment/Manage');
})->name('treatments.index');

Route::get('/restfulapi', function () {
    return Inertia::render('RestfulAPI/Index');
})->name('api.index');

Route::get('/swagger', [SwaggerController::class, 'show']);

// Route::get('/register', [RegisterController::class,'show'])->name('register.create');
// Route::post('/register', [RegisterController::class,'register'])->name('register.show');
// Route::get('/login', [LoginController::class,'show'])->name('login');
// Route::post('/login', [LoginController::class,'login'])->name('login.create');
// Route::post('/logout', [LoginController::class,'logout'])->name('login.destroy');

Route::fallback(function () {
    return redirect('/');
});