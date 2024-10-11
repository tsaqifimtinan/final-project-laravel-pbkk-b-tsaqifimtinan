<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Payments",
 *     description="API Endpoints for Payments"
 * )
 */
class PaymentController {
    /**
     * @OA\Get(
     *     path="/payments",
     *     tags={"Payments"},
     *     summary="Get list of payments",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function index () {
        $payments = Payment::paginate(10);
        return response()->json($payments);
    }

    /**
     * @OA\Post(
     *     path="/payments",
     *     tags={"Payments"},
     *     summary="Create a new payment",
     *     @OA\Response(
     *         response=201,
     *         description="Payment created"
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/payments/{payment}",
     *     tags={"Payments"},
     *     summary="Update a payment",
     *     @OA\Parameter(
     *         name="payment",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment updated"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/payments/{payment}",
     *     tags={"Payments"},
     *     summary="Delete a payment",
     *     @OA\Parameter(
     *         name="payment",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Payment deleted"
     *     )
     * )
     */
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