<?php
namespace App\Http\Controllers;

use App\Models\Treatment;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Treatments",
 *     description="API Endpoints for Treatments"
 * )
 */
class TreatmentController {
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
    public function index() {
        $treatments = Treatment::paginate(10);
        return response()->json($treatments);
    }

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
    public function store (Request $request) {
        try {
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
                'treatment_name' => 'required|string',
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
    public function update (Request $request, $id) {
        try {
            $treatment = Treatment::findOrFail($id);
            $validatedData = $request->validate([
                'patient_id' => 'sometimes|integer',
                'treatment_name' => 'sometimes|string',
                'description' => 'nullable|string',
                'treatment_date' => 'sometimes|date',
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
    public function destroy(Treatment $treatment) {
        try {
            $treatment->delete();
            return response()->json(['message' => 'Treatment deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Treatment deletion failed'], 500);
        }
    }
}