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

    public array $selectedExpenses = [];
    public bool $selectAll = false;

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

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedExpenses = $this->getFilteredQuery()->where('is_approved', false)->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedExpenses = [];
        }
    }

    public function updatedSelectedExpenses()
    {
        $this->selectAll = false;
    }

    public function approveSelected()
    {
        if (empty($this->selectedExpenses)) {
            return;
        }

        Expense::whereIn('id', $this->selectedExpenses)
            ->where('is_approved', false)
            ->update([
                'is_approved' => true,
                'approved_at' => now(),
            ]);

        session()->flash('success', 'Se han aprobado ' . count($this->selectedExpenses) . ' gasto(s) correctamente.');
        $this->selectedExpenses = [];
        $this->selectAll = false;

        $this->dispatch('close-modal', 'approval-modal');
    }

    protected function getFilteredQuery()
    {
        return Expense::query()
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
    }

    public function render()
    {
        $query = clone $this->getFilteredQuery()->with(['concept', 'user']);

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
