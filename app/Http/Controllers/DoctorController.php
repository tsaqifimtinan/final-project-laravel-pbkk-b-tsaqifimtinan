<?php
namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Http\Resources\DoctorResource;

/**
 * @OA\Tag(
 *     name="Doctors",
 *     description="API Endpoints for Doctors"
 * )
 */
class DoctorController
{
    /**
     * @OA\Get(
     *     path="/doctors",
     *     tags={"Doctors"},
     *     summary="Get list of doctors",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function index()
    {
        $doctors = Doctor::paginate(10); // Paginate with 10 items per page
        return response()->json($doctors); // Return JSON response
    }

    /**
     * @OA\Post(
     *     path="/doctors",
     *     tags={"Doctors"},
     *     summary="Create a new doctor",
     *     @OA\Response(
     *         response=201,
     *         description="Doctor created"
     *     )
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
     *     path="/doctors/{doctor}",
     *     tags={"Doctors"},
     *     summary="Update a doctor",
     *     @OA\Parameter(
     *         name="doctor",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Doctor updated"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'sometimes|string|max:255',
                'specialization' => 'sometimes|string|max:255',
                'department_id' => 'sometimes|integer|exists:departments,id',
            ]);
    
            // Find the doctor by ID
            $doctor = Doctor::findOrFail($id);
    
            // Update the doctor's data
            if (isset($validatedData['name'])) {
                $doctor->name = $validatedData['name'];
            }
            if (isset($validatedData['specialization'])) {
                $doctor->specialization = $validatedData['specialization'];
            }
            if (isset($validatedData['department_id'])) {
                $doctor->department_id = $validatedData['department_id'];
            }
    
            // Handle the photo upload if present
            if ($request->hasFile('photo')) {
                // Delete the old photo if it exists
                if ($doctor->photo) {
                    \Storage::disk('public')->delete($doctor->photo);
                }
                $photoPath = $request->file('photo')->store('photos', 'public');
                $doctor->photo = $photoPath;
            }
    
            // Save the updated doctor data
            $doctor->save();
    
            // Log the updated doctor data
            \Log::info('Updated doctor data:', $doctor->toArray());
    
            // Return the updated doctor data
            return response()->json([
                'message' => 'Doctor updated successfully',
                'data' => $doctor,
            ]);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error updating doctor: ' . $e->getMessage());
    
            // Return a JSON response with the error message
            return response()->json([
                'message' => 'Error updating doctor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/doctors/{doctor}",
     *     tags={"Doctors"},
     *     summary="Delete a doctor",
     *     @OA\Parameter(
     *         name="doctor",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Doctor deleted"
     *     )
     * )
     */
    public function destroy(Doctor $doctor)
    {
        try {
            // Check if the doctor has a photo and delete it from the filesystem
            if ($doctor->photo && file_exists(public_path('photos/' . $doctor->photo))) {
                unlink(public_path('photos/' . $doctor->photo));
            }

            // Delete the doctor record from the database
            $doctor->delete();

            // Return a success response with 204 No Content status
            return response()->json(null, 204);
        } catch (\Exception $e) {
            // Catch any exceptions and return an error response
            return response()->json([
                'error' => 'Failed to delete doctor. Please try again.'
            ], 500);
        }
    }
}