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

    public function store (Request $request) {
        try {
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
                'doctor_id' => 'required|integer',
                'appointment_date' => 'required|date',
                'notes' => 'nullable|string',
            ]);

            Appointment::create([
                'patient_id' => $validatedData['patient_id'],
                'doctor_id' => $validatedData['doctor_id'],
                'appointment_date' => $validatedData['appointment_date'],
                'notes' => $validatedData['notes'],
            ]);
            
            return response()->json([
                'message' => 'Appointment created',
                'data' => $validatedData,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Appointment creation failed'], 500);
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