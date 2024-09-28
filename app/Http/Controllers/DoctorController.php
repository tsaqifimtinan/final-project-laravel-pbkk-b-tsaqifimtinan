<?php
namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController
{
    public function index()
    {
        $doctors = Doctor::paginate(10); // Paginate with 10 items per page
        return response()->json($doctors); // Return JSON response
    }

    public function store(Request $request)
    {
        $doctor = Doctor::create($request->all());
        return response()->json($doctor, 201); // Return JSON response with 201 status code
    }

    public function update(Request $request, Doctor $doctor)
    {
        $doctor->update($request->all());
        return response()->json($doctor);
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return response()->json(null, 204); // Return JSON response with 204 status code
    }
}