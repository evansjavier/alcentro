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
    public $perPage = 10;
    
    protected $queryString = [
        "search" => ["except" => ""],
        "method" => ["except" => "all"],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingMethod()
    {
        $this->resetPage();
    }

    public function render()
    {
        $payments = Payment::with(["invoice.client:id,name,tax_id"])
            ->when($this->method !== "all", function ($query) {
                $query->where("method", $this->method);
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

