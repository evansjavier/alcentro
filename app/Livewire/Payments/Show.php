<?php

namespace App\Livewire\Payments;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Show extends Component
{
    public Payment $payment;

    public function mount(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function approvePayment()
    {
        if ($this->payment->is_approved) {
            return;
        }

        // Validate max amount before approving
        $otherPaymentsSum = $this->payment->invoice->approvedPayments()->where("id", "!=", $this->payment->id)->sum("amount_received");
        $maxAmount = round($this->payment->invoice->total_amount - $otherPaymentsSum, 2);

        if (round((float) $this->payment->amount_received, 2) > $maxAmount) {
            session()->flash('error', "El monto de un pago aprobado no puede superar el saldo pendiente de $" . number_format($maxAmount, 2));
            $this->dispatch('close-modal', 'approval-modal');
            return;
        }

        DB::transaction(function () {
            $this->payment->update([
                'is_approved' => true,
                'approved_at' => now(),
            ]);

            $this->payment->invoice->recalculateStatus();
        });

        session()->flash('status', 'Pago aprobado exitosamente.');
        $this->dispatch('close-modal', 'approval-modal');
    }

    public function render()
    {
        return view('livewire.payments.show')->layout('layouts.app');
    }
}
