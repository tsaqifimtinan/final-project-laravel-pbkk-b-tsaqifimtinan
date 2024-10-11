<?php
namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Medications",
 *     description="API Endpoints for Medications"
 * )
 */

class MedicationController {
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
    public function index() {
        $medications = Medication::paginate(10);
        return response()->json($medications);
    }

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