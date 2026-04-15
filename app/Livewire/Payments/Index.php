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

    public array $selectedPayments = [];
    public bool $selectAll = false;

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

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedPayments = $this->getFilteredQuery()->where('is_approved', false)->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedPayments = [];
        }
    }

    public function updatedSelectedPayments()
    {
        $this->selectAll = false;
    }

    public function approveSelected()
    {
        if (!auth()->user()->hasRole(\App\Models\Role::ROLE_OWNER)) {
            abort(403, 'No tienes permisos para aprobar pagos.');
        }

        if (empty($this->selectedPayments)) {
            return;
        }

        Payment::whereIn('id', $this->selectedPayments)
            ->where('is_approved', false)
            ->update([
                'is_approved' => true,
                'approved_at' => now(),
            ]);

        session()->flash('success', 'Se han aprobado ' . count($this->selectedPayments) . ' pago(s) correctamente.');
        $this->selectedPayments = [];
        $this->selectAll = false;

        $this->dispatch('close-modal', 'approval-modal');
    }

    protected function getFilteredQuery()
    {
        return Payment::with(["invoice.client:id,name,tax_id"])
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
            });
    }

    public function render()
    {
        $payments = $this->getFilteredQuery()
            ->orderByDesc("payment_date")
            ->orderByDesc("id")
            ->paginate($this->perPage);

        return view("livewire.payments.index", [
            "payments" => $payments,
        ])->layout("layouts.app");
    }
}

