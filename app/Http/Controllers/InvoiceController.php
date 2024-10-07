<?php
namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController
{
    public function index() {
        $invoices = Invoice::paginate(10);
        return response()->json($invoices);
    }

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

    public function destroy (Invoice $invoice) {
        try {
            $invoice->delete();
            return response()->json(['message' => 'Invoice deleted'], 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invoice deletion failed'], 500);
        }
    }
}