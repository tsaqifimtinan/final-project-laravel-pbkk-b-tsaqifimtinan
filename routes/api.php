<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/**
 * @OA\Post(
 *     path="/register",
 *     tags={"Authentication"},
 *     summary="Register a new user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password"),
 *             @OA\Property(property="password_confirmation", type="string", format="password", example="password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User registered successfully"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error"
 *     )
 * )
 */
Route::post('/register', [RegisterController::class, 'register']);

/**
 * @OA\Post(
 *     path="/login",
 *     tags={"Authentication"},
 *     summary="Login a user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User logged in successfully"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Invalid credentials"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error"
 *     )
 * )
 */
Route::post('/login', [LoginController::class, 'login']);

/**
 * @OA\Post(
 *     path="/logout",
 *     tags={"Authentication"},
 *     summary="Logout a user",
 *     @OA\Response(
 *         response=200,
 *         description="User logged out successfully"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error"
 *     )
 * )
 */
Route::post('/logout', [LoginController::class, 'logout']);

/**
 * @OA\Tag(
 *     name="Doctors",
 *     description="API Endpoints for Doctors"
 * )
 */

/**
 * @OA\Get(
 *     path="/doctors",
 *     tags={"Doctors"},
 *     summary="Get list of doctors",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     )
 * )
 */
Route::get('/doctors', [DoctorController::class, 'index']);

/**
 * @OA\Post(
 *     path="/doctors",
 *     tags={"Doctors"},
 *     summary="Create a new doctor",
 *     @OA\Response(
 *         response=201,
 *         description="Doctor created"
 *     )
 * )
 */
Route::post('/doctors', [DoctorController::class, 'store']);

/**
 * @OA\Put(
 *     path="/doctors/{doctor}",
 *     tags={"Doctors"},
 *     summary="Update a doctor",
 *     @OA\Parameter(
 *         name="doctor",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Doctor updated"
 *     )
 * )
 */
Route::put('/doctors/{doctor}', [DoctorController::class, 'update']);

/**
 * @OA\Delete(
 *     path="/doctors/{doctor}",
 *     tags={"Doctors"},
 *     summary="Delete a doctor",
 *     @OA\Parameter(
 *         name="doctor",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Doctor deleted"
 *     )
 * )
 */
Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy']);

/**
 * @OA\Tag(
 *     name="Patients",
 *     description="API Endpoints for Patients"
 * )
 */

/**
 * @OA\Get(
 *     path="/patients",
 *     tags={"Patients"},
 *     summary="Get list of patients",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     )
 * )
 */
Route::get('/patients', [PatientController::class, 'index']);

/**
 * @OA\Post(
 *     path="/patients",
 *     tags={"Patients"},
 *     summary="Create a new patient",
 *     @OA\Response(
 *         response=201,
 *         description="Patient created"
 *     )
 * )
 */
Route::post('/patients', [PatientController::class, 'store']);

/**
 * @OA\Put(
 *     path="/patients/{patient}",
 *     tags={"Patients"},
 *     summary="Update a patient",
 *     @OA\Parameter(
 *         name="patient",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Patient updated"
 *     )
 * )
 */
Route::put('/patients/{patient}', [PatientController::class, 'update']);

/**
 * @OA\Delete(
 *     path="/patients/{patient}",
 *     tags={"Patients"},
 *     summary="Delete a patient",
 *     @OA\Parameter(
 *         name="patient",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Patient deleted"
 *     )
 * )
 */
Route::delete('/patients/{patient}', [PatientController::class, 'destroy']);

/**
 * @OA\Tag(
 *     name="Appointments",
 *     description="API Endpoints for Appointments"
 * )
 */

/**
 * @OA\Get(
 *     path="/appointments",
 *     tags={"Appointments"},
 *     summary="Get list of appointments",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     )
 * )
 */
Route::get('/appointments', [AppointmentController::class, 'index']);

/**
 * @OA\Post(
 *     path="/appointments",
 *     tags={"Appointments"},
 *     summary="Create a new appointment",
 *     @OA\Response(
 *         response=201,
 *         description="Appointment created"
 *     )
 * )
 */
Route::post('/appointments', [AppointmentController::class, 'store']);

/**
 * @OA\Put(
 *     path="/appointments/{appointment}",
 *     tags={"Appointments"},
 *     summary="Update an appointment",
 *     @OA\Parameter(
 *         name="appointment",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Appointment updated"
 *     )
 * )
 */
Route::put('/appointments/{appointment}', [AppointmentController::class, 'update']);

/**
 * @OA\Delete(
 *     path="/appointments/{appointment}",
 *     tags={"Appointments"},
 *     summary="Delete an appointment",
 *     @OA\Parameter(
 *         name="appointment",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Appointment deleted"
 *     )
 * )
 */
Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy']);

/**
 * @OA\Tag(
 *     name="Invoices",
 *     description="API Endpoints for Invoices"
 * )
 */

/**
 * @OA\Get(
 *     path="/invoices",
 *     tags={"Invoices"},
 *     summary="Get list of invoices",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     )
 * )
 */
Route::get('/invoices', [InvoiceController::class, 'index']);

/**
 * @OA\Post(
 *     path="/invoices",
 *     tags={"Invoices"},
 *     summary="Create a new invoice",
 *     @OA\Response(
 *         response=201,
 *         description="Invoice created"
 *     )
 * )
 */
Route::post('/invoices', [InvoiceController::class, 'store']);

/**
 * @OA\Put(
 *     path="/invoices/{invoice}",
 *     tags={"Invoices"},
 *     summary="Update an invoice",
 *     @OA\Parameter(
 *         name="invoice",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Invoice updated"
 *     )
 * )
 */
Route::put('/invoices/{invoice}', [InvoiceController::class, 'update']);

/**
 * @OA\Delete(
 *     path="/invoices/{invoice}",
 *     tags={"Invoices"},
 *     summary="Delete an invoice",
 *     @OA\Parameter(
 *         name="invoice",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Invoice deleted"
 *     )
 * )
 */
Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy']);

/**
 * @OA\Tag(
 *     name="Medications",
 *     description="API Endpoints for Medications"
 * )
 */

/**
 * @OA\Get(
 *     path="/medications",
 *     tags={"Medications"},
 *     summary="Get list of medications",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     )
 * )
 */
Route::get('/medications', [MedicationController::class, 'index']);

/**
 * @OA\Post(
 *     path="/medications",
 *     tags={"Medications"},
 *     summary="Create a new medication",
 *     @OA\Response(
 *         response=201,
 *         description="Medication created"
 *     )
 * )
 */
Route::post('/medications', [MedicationController::class, 'store']);

/**
 * @OA\Put(
 *     path="/medications/{medication}",
 *     tags={"Medications"},
 *     summary="Update a medication",
 *     @OA\Parameter(
 *         name="medication",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Medication updated"
 *     )
 * )
 */
Route::put('/medications/{medication}', [MedicationController::class, 'update']);

/**
 * @OA\Delete(
 *     path="/medications/{medication}",
 *     tags={"Medications"},
 *     summary="Delete a medication",
 *     @OA\Parameter(
 *         name="medication",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Medication deleted"
 *     )
 * )
 */
Route::delete('/medications/{medication}', [MedicationController::class, 'destroy']);

/**
 * @OA\Tag(
 *     name="Payments",
 *     description="API Endpoints for Payments"
 * )
 */

/**
 * @OA\Get(
 *     path="/payments",
 *     tags={"Payments"},
 *     summary="Get list of payments",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     )
 * )
 */
Route::get('/payments', [PaymentController::class, 'index']);

/**
 * @OA\Post(
 *     path="/payments",
 *     tags={"Payments"},
 *     summary="Create a new payment",
 *     @OA\Response(
 *         response=201,
 *         description="Payment created"
 *     )
 * )
 */
Route::post('/payments', [PaymentController::class, 'store']);

/**
 * @OA\Put(
 *     path="/payments/{payment}",
 *     tags={"Payments"},
 *     summary="Update a payment",
 *     @OA\Parameter(
 *         name="payment",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Payment updated"
 *     )
 * )
 */
Route::put('/payments/{payment}', [PaymentController::class, 'update']);

/**
 * @OA\Delete(
 *     path="/payments/{payment}",
 *     tags={"Payments"},
 *     summary="Delete a payment",
 *     @OA\Parameter(
 *         name="payment",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Payment deleted"
 *     )
 * )
 */
Route::delete('/payments/{payment}', [PaymentController::class, 'destroy']);

/**
 * @OA\Tag(
 *     name="Prescriptions",
 *     description="API Endpoints for Prescriptions"
 * )
 */

/**
 * @OA\Get(
 *     path="/prescriptions",
 *     tags={"Prescriptions"},
 *     summary="Get list of prescriptions",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     )
 * )
 */
Route::get('/prescriptions', [PrescriptionController::class, 'index']);

/**
 * @OA\Post(
 *     path="/prescriptions",
 *     tags={"Prescriptions"},
 *     summary="Create a new prescription",
 *     @OA\Response(
 *         response=201,
 *         description="Prescription created"
 *     )
 * )
 */
Route::post('/prescriptions', [PrescriptionController::class, 'store']);

/**
 * @OA\Put(
 *     path="/prescriptions/{prescription}",
 *     tags={"Prescriptions"},
 *     summary="Update a prescription",
 *     @OA\Parameter(
 *         name="prescription",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Prescription updated"
 *     )
 * )
 */
Route::put('/prescriptions/{prescription}', [PrescriptionController::class, 'update']);

/**
 * @OA\Delete(
 *     path="/prescriptions/{prescription}",
 *     tags={"Prescriptions"},
 *     summary="Delete a prescription",
 *     @OA\Parameter(
 *         name="prescription",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Prescription deleted"
 *     )
 * )
 */
Route::delete('/prescriptions/{prescription}', [PrescriptionController::class, 'destroy']);

/**
 * @OA\Tag(
 *     name="Rooms",
 *     description="API Endpoints for Rooms"
 * )
 */

/**
 * @OA\Get(
 *     path="/rooms",
 *     tags={"Rooms"},
 *     summary="Get list of rooms",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     )
 * )
 */
Route::get('/rooms', [RoomController::class, 'index']);

/**
 * @OA\Post(
 *     path="/rooms",
 *     tags={"Rooms"},
 *     summary="Create a new room",
 *     @OA\Response(
 *         response=201,
 *         description="Room created"
 *     )
 * )
 */
Route::post('/rooms', [RoomController::class, 'store']);

/**
 * @OA\Put(
 *     path="/rooms/{room}",
 *     tags={"Rooms"},
 *     summary="Update a room",
 *     @OA\Parameter(
 *         name="room",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Room updated"
 *     )
 * )
 */
Route::put('/rooms/{room}', [RoomController::class, 'update']);

/**
 * @OA\Delete(
 *     path="/rooms/{room}",
 *     tags={"Rooms"},
 *     summary="Delete a room",
 *     @OA\Parameter(
 *         name="room",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Room deleted"
 *     )
 * )
 */
Route::delete('/rooms/{room}', [RoomController::class, 'destroy']);

/**
 * @OA\Tag(
 *     name="Treatments",
 *     description="API Endpoints for Treatments"
 * )
 */

/**
 * @OA\Get(
 *     path="/treatments",
 *     tags={"Treatments"},
 *     summary="Get list of treatments",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     )
 * )
 */
Route::get('/treatments', [TreatmentController::class, 'index']);

/**
 * @OA\Post(
 *     path="/treatments",
 *     tags={"Treatments"},
 *     summary="Create a new treatment",
 *     @OA\Response(
 *         response=201,
 *         description="Treatment created"
 *     )
 * )
 */
Route::post('/treatments', [TreatmentController::class, 'store']);

/**
 * @OA\Put(
 *     path="/treatments/{treatment}",
 *     tags={"Treatments"},
 *     summary="Update a treatment",
 *     @OA\Parameter(
 *         name="treatment",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Treatment updated"
 *     )
 * )
 */
Route::put('/treatments/{treatment}', [TreatmentController::class, 'update']);

/**
 * @OA\Delete(
 *     path="/treatments/{treatment}",
 *     tags={"Treatments"},
 *     summary="Delete a treatment",
 *     @OA\Parameter(
 *         name="treatment",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Treatment deleted"
 *     )
 * )
 */
Route::delete('/treatments/{treatment}', [TreatmentController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user-role', [UserController::class, 'getUserRole']);