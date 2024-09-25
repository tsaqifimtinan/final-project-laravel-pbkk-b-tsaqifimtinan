<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController
{
    public function index()
    {
        $data['appointments'] = Appointment::paginate(10);
        return view('appointment.index', compact('data'));
    }
}