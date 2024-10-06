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
    public function store(Request $request)
    {
        try {
            // Validate the incoming request, including the photo
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'specialization' => 'required|string',
                'department_id' => 'required|integer',
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // validate image file
            ]);

            // Handle the photo upload
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('photos'), $photoName); // Move the file to a public folder
            }

            // Store the data in the database, including the photo path
            Doctor::create([
                'name' => $validatedData['name'],
                'specialization' => $validatedData['specialization'],
                'department_id' => $validatedData['department_id'],
                'photo' => $photoName, // Save the photo name/path in the database
            ]);

            // Return a success response
            return response()->json([
                'message' => 'Doctor added successfully',
                'data' => $validatedData,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Catch validation exceptions and return the errors
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
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