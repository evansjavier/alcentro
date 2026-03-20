<?php

namespace App\Livewire\Payments;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    public Invoice $invoice;
    public $amount_received;
    public $payment_date;
    public $method = "wire_transfer";
    public $reference_number;
    public $notes;

    public function getMaxAmountProperty()
    {
        return round($this->invoice->total_amount - $this->invoice->paid_amount, 2);
    }

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->payment_date = date("Y-m-d");
        $this->amount_received = max(0, $invoice->total_amount - $invoice->paid_amount);
    }

    public function save()
    {
        $this->validate([
            "amount_received" => "required|numeric|min:0.01",
            "payment_date" => "required|date",
            "method" => "required|in:cash,wire_transfer",
            "reference_number" => "nullable|string|max:255",
            "notes" => "nullable|string",
        ]);

        $maxAmount = round($this->invoice->total_amount - $this->invoice->paid_amount, 2);

        DB::transaction(function () {
            // Create payment
            $payment = Payment::create([
                "invoice_id" => $this->invoice->id,
                "amount_received" => $this->amount_received,
                "payment_date" => $this->payment_date,
                "method" => $this->method,
                "is_taxable" => $this->method === "wire_transfer",
                "reference_number" => $this->reference_number,
                "notes" => $this->notes,
                "is_approved" => false,
                "approved_at" => null,
            ]);

            // Update invoice
            $this->invoice->recalculateStatus();
        });

        session()->flash("status", "Pago registrado exitosamente.");

        return redirect()->route("invoices.show", $this->invoice);
    }

    public function render()
    {
        return view("livewire.payments.create")->layout("layouts.app");
    }
}

