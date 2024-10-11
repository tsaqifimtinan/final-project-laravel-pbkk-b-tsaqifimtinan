<?php
namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController {
    public function index() {
        $prescriptions = Prescription::paginate(10);
        return response()->json($prescriptions);
    }

    public function store (Request $request) {
        try {
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
                'prescription_name' => 'required|integer',
                'prescription_date' => 'required|date',
                'description' => 'nullable|string',
            ]);

            Prescription::create([
                'patient_id' => $validatedData['patient_id'],
                'prescription_name' => $validatedData['prescription_name'],
                'prescription_date' => $validatedData['prescription_date'],
                'description' => $validatedData['description'],
            ]);
            
            return response()->json([
                'message' => 'Prescription created',
                'data' => $validatedData,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Prescription creation failed'], 500);
        }
    }

    public function update(Request $request, $id) {
        try {
            $prescription = Prescription::findOrFail($id);
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
                'prescription_name' => 'required|string',
                'prescription_date' => 'required|date',
                'description' => 'nullable|string',
            ]);
    
            $prescription->update([
                'patient_id' => $validatedData['patient_id'],
                'prescription_name' => $validatedData['prescription_name'],
                'prescription_date' => $validatedData['prescription_date'],
                'description' => $validatedData['description'],
            ]);
    
            return response()->json([
                'message' => 'Prescription updated',
                'data' => $validatedData,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Prescription update failed: ' . $e->getMessage());
            return response()->json(['message' => 'Prescription update failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Prescription $prescription) {
        try {
            $prescription->delete();
            return response()->json(['message' => 'Prescription deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Prescription deletion failed'], 500);
        }
    }
}