<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
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
    public int $perPage = 12;

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
        $contracts = Contract::with(['client:id,name', 'premise:id,code'])
            ->when($this->status !== 'all', function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->search, function ($query) {
                $term = '%' . $this->search . '%';
                $query->where(function ($q) use ($term) {
                    $q->whereHas('client', fn ($c) => $c->where('name', 'like', $term))
                        ->orWhereHas('premise', fn ($p) => $p->where('code', 'like', $term));
                });
            })
            ->when($this->sort === 'oldest', fn ($q) => $q->orderBy('created_at'))
            ->when($this->sort === 'latest', fn ($q) => $q->orderByDesc('created_at'))
            ->paginate($this->perPage);

        return view('livewire.contracts.index', [
            'contracts' => $contracts,
        ]);
    }
}
