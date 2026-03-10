<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = 'all';
    public string $sort = 'latest';
    public int $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
        'sort' => ['except' => 'latest'],
        'page' => ['except' => 1],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingSort(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $invoices = Invoice::with(['client:id,name,tax_id'])
            ->when($this->status !== 'all', function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->search, function ($query) {
                $term = '%' . $this->search . '%';
                $query->where(function ($q) use ($term) {
                    $q->where('period', 'like', $term)
                      ->orWhereHas('client', fn ($c) => $c->where('name', 'like', $term)->orWhere('tax_id', 'like', $term));
                });
            })
            ->when($this->sort === 'oldest', fn ($q) => $q->orderBy('due_date'))
            ->when($this->sort === 'latest', fn ($q) => $q->orderByDesc('due_date'))
            ->paginate($this->perPage);

        return view('livewire.invoices.index', [
            'invoices' => $invoices,
        ]);
    }
}
