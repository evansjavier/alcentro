<?php

namespace App\Livewire\Payments;

use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public Payment $payment;
    public Invoice $invoice;
    public $amount_received;
    public $payment_date;
    public $method;
    public $reference_number;
    public $notes;
    public $is_approved = false;

    public function getMaxAmountProperty()
    {
        $otherPaymentsSum = $this->invoice->approvedPayments()
            ->where("id", "!=", $this->payment->id)
            ->sum("amount_received");

        return round($this->invoice->total_amount - $otherPaymentsSum, 2);
    }

    public function mount(Payment $payment)
    {
        $this->payment = $payment;
        $this->invoice = $payment->invoice;

        $this->amount_received = $payment->amount_received;
        $this->payment_date = $payment->payment_date->format("Y-m-d");
        $this->method = $payment->method;
        $this->reference_number = $payment->reference_number;
        $this->notes = $payment->notes;
        $this->is_approved = $payment->is_approved;
    }

    public function save()
    {
        if ($this->payment->is_approved) {
            $this->addError("amount_received", "No se puede editar un pago aprobado");
            return;
        }

        $this->validate([
            "amount_received" => "required|numeric|min:0.01",
            "payment_date" => "required|date",
            "method" => "required|in:cash,wire_transfer",
            "reference_number" => "nullable|string|max:255",
            "notes" => "nullable|string",
        ]);

        DB::transaction(function () {
            // Update payment
            $this->payment->update([
                "amount_received" => $this->amount_received,
                "payment_date" => $this->payment_date,
                "method" => $this->method,
                "is_taxable" => $this->method === "wire_transfer",
                "reference_number" => $this->reference_number,
                "notes" => $this->notes,
            ]);

            // Update invoice
            $this->invoice->recalculateStatus();
        });

        session()->flash("status", "Pago actualizado exitosamente.");

        return redirect()->route("invoices.show", $this->invoice);
    }

    public function render()
    {
        return view("livewire.payments.create")->layout("layouts.app");
    }
}

