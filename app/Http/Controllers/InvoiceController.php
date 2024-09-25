<?php
namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController
{
    public function index()
    {
        $invoices = Invoice::all();
        return view('tables.invoices', compact('invoices'));
    }
}