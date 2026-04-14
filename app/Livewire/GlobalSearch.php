<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Client;
use App\Models\Premise;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Contract;
use Illuminate\Support\Collection;

class GlobalSearch extends Component
{
    public $query = '';

    public function render()
    {
        $clients = new Collection();
        $premises = new Collection();
        $invoices = new Collection();
        $payments = new Collection();
        $contracts = new Collection();

        if (!empty($this->query)) {
            $clients = Client::where('name', 'like', '%' . $this->query . '%')
                ->orWhere('tax_id', 'like', '%' . $this->query . '%')
                ->limit(5)
                ->get();

            $premises = Premise::where('code', 'like', '%' . $this->query . '%')
                ->limit(5)
                ->get();

            $invoices = Invoice::where('id', 'like', '%' . $this->query . '%')
                ->limit(5)
                ->get();

            $payments = Payment::where('reference_number', 'like', '%' . $this->query . '%')
                ->limit(5)
                ->get();

            $contracts = Contract::with(['client', 'premise'])
                ->where('status', Contract::STATUS_ACTIVO)
                ->where(function($q) {
                    $q->whereHas('premise', function($q2) {
                        $q2->where('code', 'like', '%' . $this->query . '%');
                    })->orWhereHas('client', function($q2) {
                        $q2->where('name', 'like', '%' . $this->query . '%');
                    });
                })
                ->limit(5)
                ->get();
        }

        return view('livewire.global-search', [
            'clients' => $clients,
            'premises' => $premises,
            'invoices' => $invoices,
            'payments' => $payments,
            'contracts' => $contracts,
        ]);
    }
}
