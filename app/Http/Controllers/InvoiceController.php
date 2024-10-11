<?php
namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Invoices",
 *     description="API Endpoints for Invoices"
 * )
 */
class InvoiceController
{
    /**
     * @OA\Get(
     *     path="/invoices",
     *     tags={"Invoices"},
     *     summary="Get list of invoices",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function index() {
        $invoices = Invoice::paginate(10);
        return response()->json($invoices);
    }

    /**
     * @OA\Post(
     *     path="/invoices",
     *     tags={"Invoices"},
     *     summary="Create a new invoice",
     *     @OA\Response(
     *         response=201,
     *         description="Invoice created"
     *     )
     * )
     */
    public function store (Request $request) {
        try {
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
                'invoice_number' => 'required|string',
                'description' => 'required|string',
                'total_amount' => 'required|integer',
            ]);

            Invoice::create([
                'patient_id' => $validatedData['patient_id'],
                'invoice_number' => $validatedData['invoice_number'],
                'description' => $validatedData['description'],
                'total_amount' => $validatedData['total_amount'],
            ]);
            
            return response()->json([
                'message' => 'Invoice created',
                'data' => $validatedData,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invoice creation failed'], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/invoices/{invoice}",
     *     tags={"Invoices"},
     *     summary="Update an invoice",
     *     @OA\Parameter(
     *         name="invoice",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoice updated"
     *     )
     * )
     */
    public function update (Request $request, $id) {
        try {
            $invoice = Invoice::findOrFail($id);
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
                'invoice_number' => 'required|string',
                'description' => 'required|string',
                'total_amount' => 'required|integer',
            ]);

            $invoice->update([
                'patient_id' => $validatedData['patient_id'],
                'invoice_number' => $validatedData['invoice_number'],
                'description' => $validatedData['description'],
                'total_amount' => $validatedData['total_amount'],
            ]);

            return response()->json([
                'message' => 'Invoice updated',
                'data' => $validatedData,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invoice update failed'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/invoices/{invoice}",
     *     tags={"Invoices"},
     *     summary="Delete an invoice",
     *     @OA\Parameter(
     *         name="invoice",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Invoice deleted"
     *     )
     * )
     */
    public function destroy (Invoice $invoice) {
        try {
            $invoice->delete();
            return response()->json(['message' => 'Invoice deleted'], 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invoice deletion failed'], 500);
        }
    }
}