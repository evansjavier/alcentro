<?php

namespace App\Livewire\Premises;

use App\Models\Premise;
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
        $premises = Premise::query()
            ->with(['activeContract.client'])
            ->when($this->search, function ($query) {
                $term = '%' . $this->search . '%';
                $query->where('code', 'like', $term);
            })
            ->when($this->status !== 'all', function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->sort === 'code', function ($query) {
                $query->orderBy('code');
            })
            ->when($this->sort === 'size_desc', function ($query) {
                $query->orderByDesc('square_meters');
            })
            ->when($this->sort === 'rent_desc', function ($query) {
                $query->orderByDesc('suggested_rent');
            })
            ->when($this->sort === 'oldest', function ($query) {
                $query->orderBy('created_at');
            })
            ->when($this->sort === 'latest', function ($query) {
                $query->orderByDesc('created_at');
            })
            ->paginate($this->perPage);

        return view('livewire.premises.index', [
            'premises' => $premises,
        ]);
    }
}
