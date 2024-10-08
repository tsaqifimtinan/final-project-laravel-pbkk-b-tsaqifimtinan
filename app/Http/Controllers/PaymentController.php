<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController {
    public function index () {
        $payments = Payment::paginate(10);
        return response()->json($payments);
    }

    public function store (Request $request) {
        try {
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
                'payment_method' => 'required|string',
                'description' => 'nullable|string',
                'payment_date' => 'required|date',
            ]);

            Payment::create([
                'patient_id' => $validatedData['patient_id'],
                'payment_method' => $validatedData['payment_method'],
                'description' => $validatedData['description'],
                'payment_date' => $validatedData['payment_date'],
            ]);

            return response()->json([
                'message' => 'Payment created',
                'data' => $validatedData,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Payment creation failed'], 500);
        }
    }

    public function update (Request $request, $id) {
        try {
            $payment = Payment::findOrFail($id);
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
                'payment_method' => 'required|string',
                'description' => 'nullable|string',
                'payment_date' => 'required|date',
            ]);

            $payment->update([
                'patient_id' => $validatedData['patient_id'],
                'payment_method' => $validatedData['payment_method'],
                'description' => $validatedData['description'],
                'payment_date' => $validatedData['payment_date'],
            ]);

            return response()->json([
                'message' => 'Payment updated',
                'data' => $validatedData,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Payment update failed'], 500);
        }
    }

    public function destroy($id) {
        try {
            $payment = Payment::findOrFail($id);
            $payment->delete();
            return response()->json(['message' => 'Payment deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Payment deletion failed'], 500);
        }
    }
}