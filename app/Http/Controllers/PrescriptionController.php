<?php
namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Prescriptions",
 *     description="API Endpoints for Prescriptions"
 * )
 */
class PrescriptionController {
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
    public function index() {
        $prescriptions = Prescription::paginate(10);
        return response()->json($prescriptions);
    }

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
    public function store (Request $request) {
        try {
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
                'prescription_name' => 'required|string',
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
    public function destroy(Prescription $prescription) {
        try {
            $prescription->delete();
            return response()->json(['message' => 'Prescription deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Prescription deletion failed'], 500);
        }
    }
}