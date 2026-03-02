<?php

namespace App\Livewire\Clients;

use App\Models\Client;
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
        $clients = Client::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $term = '%' . $this->search . '%';

                    $q->where('name', 'like', $term)
                        ->orWhere('tax_id', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('phone', 'like', $term);
                });
            })
            ->when($this->status === 'with_email', function ($query) {
                $query->whereNotNull('email')->where('email', '!=', '');
            })
            ->when($this->status === 'without_email', function ($query) {
                $query->where(function ($q) {
                    $q->whereNull('email')->orWhere('email', '=', '');
                });
            })
            ->when($this->sort === 'name', function ($query) {
                $query->orderBy('name');
            })
            ->when($this->sort === 'oldest', function ($query) {
                $query->orderBy('created_at');
            })
            ->when($this->sort === 'latest', function ($query) {
                $query->orderByDesc('created_at');
            })
            ->paginate($this->perPage);

        return view('livewire.clients.index', [
            'clients' => $clients,
        ]);
    }
}
