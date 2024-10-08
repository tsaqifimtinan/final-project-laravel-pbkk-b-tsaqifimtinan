<?php
namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;

class MedicationController {
    public function index() {
        $medications = Medication::paginate(10);
        return response()->json($medications);
    }

    public function store(Request $request) {
        try {
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
                'medication_name' => 'required|string',
                'description' => 'nullable|string',
                'medication_date' => 'required|date',
            ]);

            Medication::create([
                'patient_id' => $validatedData['patient_id'],
                'medication_name' => $validatedData['medication_name'],
                'description' => $validatedData['description'],
                'medication_date' => $validatedData['medication_date'],
            ]);

            return response()->json([
                'message' => 'Medication created',
                'data' => $validatedData,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Medication creation failed'], 500);
        }
    }

    public function update (Request $request, $id) {
        try {
            $medication = Medication::findOrFail($id);
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
                'medication_name' => 'required|string',
                'description' => 'nullable|string',
                'medication_date' => 'required|date',
            ]);

            $medication->update([
                'patient_id' => $validatedData['patient_id'],
                'medication_name' => $validatedData['medication_name'],
                'description' => $validatedData['description'],
                'medication_date' => $validatedData['medication_date'],
            ]);

            return response()->json([
                'message' => 'Medication updated',
                'data' => $validatedData,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Medication update failed'], 500);
        }
    }

    public function destroy($id) {
        try {
            $medication = Medication::findOrFail($id);
            $medication->delete();
            return response()->json(['message' => 'Medication deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Medication deletion failed'], 500);
        }
    }
}