<?php

namespace App\Livewire\ExpenseConcepts;

use App\Models\ExpenseConcept;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 30;

    // Propiedades del formulario
    public $conceptId = null;
    public string $name = '';
    public bool $is_active = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'is_active' => 'boolean',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function createConcept()
    {
        $this->reset(['conceptId', 'name', 'is_active']);
        $this->is_active = true;
        $this->resetValidation();
        $this->dispatch('open-modal', 'concept-modal');
    }

    public function editConcept(ExpenseConcept $concept)
    {
        $this->conceptId = $concept->id;
        $this->name = $concept->name;
        $this->is_active = $concept->is_active;

        $this->resetValidation();
        $this->dispatch('open-modal', 'concept-modal');
    }

    public function save()
    {
        $this->validate();

        if ($this->conceptId) {
            $concept = ExpenseConcept::find($this->conceptId);
            if ($concept) {
                $concept->update([
                    'name' => $this->name,
                    'is_active' => $this->is_active,
                ]);
            }
        } else {
            ExpenseConcept::create([
                'name' => $this->name,
                'is_active' => $this->is_active,
            ]);
        }

        $this->dispatch('close-modal', 'concept-modal');
        $this->dispatch('concept-saved');
        session()->flash('success', $this->conceptId ? 'Concepto de gasto actualizado.' : 'Concepto de gasto creado.');
    }

    public function toggleActive(ExpenseConcept $concept)
    {
        $concept->update(['is_active' => !$concept->is_active]);
        session()->flash('success', 'Estado del concepto de gasto actualizado.');
    }

    public function render()
    {
        $concepts = ExpenseConcept::query()
            ->when($this->search, function ($query) {
                $term = '%' . $this->search . '%';
                $query->where('name', 'like', $term);
            })
            ->orderBy('name')
            ->paginate($this->perPage);
        return view('livewire.expense-concepts.index', [
            'concepts' => $concepts,
        ]);
    }
}
