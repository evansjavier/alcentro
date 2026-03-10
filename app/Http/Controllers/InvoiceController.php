<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function show(Invoice $invoice): View
    {
        $invoice->load(['client', 'contract.premise', 'items', 'payments']);

        return view('invoices.show', compact('invoice'));
    }
}
