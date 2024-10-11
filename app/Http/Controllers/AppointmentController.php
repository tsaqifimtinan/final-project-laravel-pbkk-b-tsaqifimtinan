<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController
{
    public function index() {
        $appointments = Appointment::paginate(10);
        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'patient_id' => 'required|integer|exists:patients,id',
                'doctor_id' => 'required|integer|exists:doctors,id',
                'appointment_date' => 'required|date',
                'notes' => 'nullable|string',
            ]);
    
            // Create a new appointment with the validated data
            $appointment = Appointment::create($validatedData);
    
            // Log the created appointment data
            \Log::info('Created appointment data:', $appointment->toArray());
    
            // Return the created appointment data
            return response()->json([
                'message' => 'Appointment created successfully',
                'data' => $appointment,
            ], 201);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error creating appointment: ' . $e->getMessage());
    
            // Return a JSON response with the error message
            return response()->json([
                'message' => 'Error creating appointment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update (Request $request, $id) {
        try {
            $appointment = Appointment::findOrFail($id);
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
                'doctor_id' => 'required|integer',
                'appointment_date' => 'required|date',
                'notes' => 'nullable|string',
            ]);

            $appointment->update([
                'patient_id' => $validatedData['patient_id'],
                'doctor_id' => $validatedData['doctor_id'],
                'appointment_date' => $validatedData['appointment_date'],
                'notes' => $validatedData['notes'],
            ]);

            return response()->json([
                'message' => 'Appointment updated',
                'data' => $validatedData,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Appointment update failed'], 500);
        }
    }

    public function destroy (Appointment $appointment) {
        try {
            $appointment->delete();
            return response()->json(['message' => 'Appointment deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Appointment deletion failed'], 500);
        }
    }
}