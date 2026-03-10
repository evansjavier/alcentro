<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function show(Invoice $invoice): View
    {
        $invoice->load(['contract.client', 'contract.premise', 'payments']);

        return view('invoices.show', compact('invoice'));
    }
}
