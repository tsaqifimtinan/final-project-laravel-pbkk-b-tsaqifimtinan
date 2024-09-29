<?php
namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Http\Requests\Buku\NewDoctorRequest;
use App\Http\Resources\DoctorResource;

class DoctorController
{
    /**
     * @OA\Get(
     *     path="/api/doctors",
     *     tags={"Doctors"},
     *     @OA\Response(response="200", description="An doctors endpoint")
     * )
     */
    public function index()
    {
        $doctors = Doctor::paginate(10); // Paginate with 10 items per page
        return response()->json($doctors); // Return JSON response
    }

    /**
     * @OA\Post(
     *     path="/api/doctors",
     *     tags={"Doctors"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="specialization", type="string")
     *             @OA\Property(property="department_id", type="integer"),
     *         )
     *     ),
     *     @OA\Response(response="201", description="Doctor created")
     * )
     */
    public function store(NewDoctorRequest $request)
    {
        try {
            $doctor = Doctor::create($request->validated());
            return response()->json(new DoctorResource($doctor), 201);
        } catch (\Exception $e) {
            Log::error('Error creating doctor: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/doctors/{doctor}",
     *     tags={"Doctors"},
     *     @OA\Parameter(
     *         name="doctor",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="specialization", type="string")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Doctor updated")
     * )
     */
    public function update(Request $request, Doctor $doctor)
    {
        $doctor->update($request->all());
        return response()->json($doctor);
    }

    /**
     * @OA\Delete(
     *     path="/api/doctors/{doctor}",
     *     tags={"Doctors"},
     *     @OA\Parameter(
     *         name="doctor",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="Doctor deleted")
     * )
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return response()->json(null, 204); // Return JSON response with 204 status code
    }
}