<?php
namespace App\Http\Controllers;

use App\Models\Treatment;
use Illuminate\Http\Request;

class TreatmentController {
    public function index() {
        $treatments = Treatment::paginate(10);
        return response()->json($treatments);
    }

    public function store (Request $request) {
        try {
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
                'treatment_name' => 'required|integer',
                'description' => 'nullable|string',
                'treatment_date' => 'required|date',
            ]);

            Treatment::create([
                'patient_id' => $validatedData['patient_id'],
                'treatment_name' => $validatedData['treatment_name'],
                'description' => $validatedData['description'],
                'treatment_date' => $validatedData['treatment_date'],
            ]);
            
            return response()->json([
                'message' => 'Treatment created',
                'data' => $validatedData,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Treatment creation failed'], 500);
        }
    }

    public function update (Request $request, $id) {
        try {
            $treatment = Treatment::findOrFail($id);
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
                'treatment_name' => 'required|integer',
                'description' => 'nullable|string',
                'treatment_date' => 'required|date',
            ]);

            $treatment->update([
                'patient_id' => $validatedData['patient_id'],
                'treatment_name' => $validatedData['treatment_name'],
                'description' => $validatedData['description'],
                'treatment_date' => $validatedData['treatment_date'],
            ]);

            return response()->json([
                'message' => 'Treatment updated',
                'data' => $validatedData,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Treatment update failed'], 500);
        }
    }

    public function destroy(Treatment $treatment) {
        try {
            $treatment->delete();
            return response()->json(['message' => 'Treatment deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Treatment deletion failed'], 500);
        }
    }
}