<?php
namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use App\Http\Requests\NewPatientRequest;
use App\Http\Resources\PatientResource;

class PatientController
{
    /**
     * @OA\Get(
     *     path="/api/patients",
     *     tags={"Patients"},
     *     @OA\Response(response="200", description="A patients endpoint")
     * )
     */
    public function index()
    {
        $patients = Patient::paginate(10); // Paginate with 10 items per page
        return response()->json($patients); // Return JSON response
    }

    /**
     * @OA\Post(
     *     path="/api/patients",
     *     tags={"Patients"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="age", type="integer", example=30),
     *             @OA\Property(property="gender", type="string", example="Male"),
     *             @OA\Property(property="address", type="string", example="123 Main St")
     *         )
     *     ),
     *     @OA\Response(response="201", description="Patient created")
     * )
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'date_of_birth' => 'required|date',
                'gender' => 'required|string|max:10',
                'address' => 'required|string|max:255',
            ]);
    
            // Create a new patient with the validated data
            $patient = Patient::create($validatedData);
    
            // Log the created patient data
            \Log::info('Created patient data:', $patient->toArray());
    
            // Return the created patient data
            return response()->json([
                'message' => 'Patient created successfully',
                'data' => $patient,
            ], 201);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error creating patient: ' . $e->getMessage());
    
            // Return a JSON response with the error message
            return response()->json([
                'message' => 'Error creating patient',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/patients/{patient}",
     *     tags={"Patients"},
     *     @OA\Parameter(
     *         name="patient",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="age", type="integer"),
     *             @OA\Property(property="gender", type="string"),
     *             @OA\Property(property="address", type="string")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Patient updated")
     * )
     */
    public function update(Request $request, Patient $patient)
    {
        try {
            $patient->update($request->all());
            return response()->json($patient);
        } catch (\Exception $e) {
            Log::error('Error updating patient: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/patients/{patient}",
     *     tags={"Patients"},
     *     @OA\Parameter(
     *         name="patient",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="Patient deleted")
     * )
     */
    public function destroy(Patient $patient)
    {
        try {
            $patient->delete();
            return response()->json(null, 204); // Return JSON response with 204 status code
        } catch (\Exception $e) {
            Log::error('Error deleting patient: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}