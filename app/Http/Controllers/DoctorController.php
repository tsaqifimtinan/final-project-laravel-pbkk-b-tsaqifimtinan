<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorController
{
    public function index()
    {
        $data['doctors'] = Doctor::paginate(10); // Adjust the number as needed
        return view('doctor.index', compact('data'));
    }

    // Other methods like create, store, show, edit, update, destroy can be added here
}