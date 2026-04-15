<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use App\Models\ExpenseConcept;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $concept_id = '';
    public string $date_from = '';
    public string $date_to = '';
    public string $status = 'all';
    public int $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'concept_id' => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
        'status' => ['except' => 'all'],
        'page' => ['except' => 1],
    ];

    public function updating($property): void
    {
        if (in_array($property, ['search', 'concept_id', 'date_from', 'date_to', 'status'])) {
            $this->resetPage();
        }
    }

    public function clearFilters()
    {
        $this->reset(['search', 'concept_id', 'date_from', 'date_to', 'status']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Expense::query()
            ->with(['concept', 'user'])
            ->when($this->status !== 'all', function ($q) {
                if ($this->status === 'approved') {
                    $q->approved();
                } else if ($this->status === 'pending') {
                    $q->where('is_approved', false);
                }
            })
            ->when($this->search, function ($q) {
                $term = '%' . $this->search . '%';
                $q->where(function ($sub) use ($term) {
                    $sub->where('reference_number', 'like', $term)
                      ->orWhere('notes', 'like', $term);
                });
            })
            ->when($this->concept_id, function ($q) {
                $q->where('expense_concept_id', $this->concept_id);
            })
            ->when($this->date_from, function ($q) {
                $q->whereDate('expense_date', '>=', $this->date_from);
            })
            ->when($this->date_to, function ($q) {
                $q->whereDate('expense_date', '<=', $this->date_to);
            });

        $totalSum = (clone $query)->sum('amount');

        $expenses = $query->orderByDesc('expense_date')
            ->orderByDesc('id')
            ->paginate($this->perPage);

        $concepts = ExpenseConcept::where('is_active', true)->orderBy('name')->get();

        return view('livewire.expenses.index', [
            'expenses' => $expenses,
            'totalSum' => $totalSum,
            'concepts' => $concepts,
        ]);
    }
}
