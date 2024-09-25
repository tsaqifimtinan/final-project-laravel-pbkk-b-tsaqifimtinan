<?php
namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController
{
    public function index()
    {
        $data[patients] = Patient::paginate(10);
        return view('patient.index', compact('data'));
    }
}