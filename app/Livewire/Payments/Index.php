<?php

namespace App\Livewire\Payments;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = "";
    public $method = "all";
    public $status = "all";
    public $perPage = 10;

    protected $queryString = [
        "search" => ["except" => ""],
        "method" => ["except" => "all"],
        "status" => ["except" => "all"],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingMethod()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function toggleApproval(Payment $payment)
    {
        if ($payment->is_approved) {
            $payment->update([
                'is_approved' => false,
                'approved_at' => null,
            ]);
        } else {
            $otherPaymentsSum = $payment->invoice->approvedPayments()->where('id', '!=', $payment->id)->sum('amount_received');
            $maxAmount = round($payment->invoice->total_amount - $otherPaymentsSum, 2);

            if (round((float) $payment->amount_received, 2) > $maxAmount) {
                session()->flash('error', 'El monto supera el saldo pendiente de la factura. No se puede aprobar desde aquí.');
                return;
            }

            $payment->invoice->recalculateStatus();
        }

        session()->flash('success', 'Estado de aprobación actualizado.');
    }

    public function render()
    {
        $payments = Payment::with(["invoice.client:id,name,tax_id"])
            ->when($this->method !== "all", function ($query) {
                $query->where("method", $this->method);
            })
            ->when($this->status !== "all", function ($query) {
                if ($this->status === 'approved') {
                    $query->approved();
                } elseif ($this->status === 'pending') {
                    $query->where('is_approved', false);
                }
            })
            ->when($this->search, function ($query) {
                $term = "%" . $this->search . "%";
                $query->where("reference_number", "like", $term)
                    ->orWhereHas("invoice", function ($q) use ($term) {
                        $q->where("period", "like", $term)
                          ->orWhereHas("client", function ($c) use ($term) {
                              $c->where("name", "like", $term)->orWhere("tax_id", "like", $term);
                          });
                    });
            })
            ->orderByDesc("payment_date")
            ->orderByDesc("id")
            ->paginate($this->perPage);

        return view("livewire.payments.index", [
            "payments" => $payments,
        ])->layout("layouts.app");
    }
}

