<?php

namespace App\Livewire\Concepts;

use App\Models\Concept;
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
    public bool $is_billable = false;
    public ?int $billing_period_months = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'is_active' => 'boolean',
        'is_billable' => 'boolean',
        'billing_period_months' => 'required_if:is_billable,true|nullable|integer|min:1',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function createConcept()
    {
        $this->reset(['conceptId', 'name', 'is_active', 'is_billable', 'billing_period_months']);
        $this->is_active = true;
        $this->is_billable = false;
        $this->billing_period_months = 1;
        $this->resetValidation();
        $this->dispatch('open-modal', 'concept-modal');
    }

    public function editConcept(Concept $concept)
    {
        $this->conceptId = $concept->id;
        $this->name = $concept->name;
        $this->is_active = $concept->is_active;
        $this->is_billable = $concept->is_billable;
        $this->billing_period_months = $concept->billing_period_months ?? 1;

        $this->resetValidation();
        $this->dispatch('open-modal', 'concept-modal');
    }

    public function save()
    {
        $this->validate();

        if ($this->conceptId) {
            $concept = Concept::find($this->conceptId);
            if ($concept) {
                $concept->update([
                    'name' => $this->name,
                    'is_active' => $this->is_active,
                    'is_billable' => $this->is_billable,
                    'billing_period_months' => $this->is_billable ? $this->billing_period_months : null,
                ]);
            }
        } else {
            Concept::create([
                'name' => $this->name,
                'is_active' => $this->is_active,
                'is_billable' => $this->is_billable,
                'billing_period_months' => $this->is_billable ? $this->billing_period_months : null,
            ]);
        }

        $this->dispatch('close-modal', 'concept-modal');
        $this->dispatch('concept-saved');
        session()->flash('success', $this->conceptId ? 'Concepto de gasto actualizado.' : 'Concepto de gasto creado.');
    }

    public function toggleActive(Concept $concept)
    {
        $concept->update(['is_active' => !$concept->is_active]);
        session()->flash('success', 'Estado del concepto de gasto actualizado.');
    }

    public function render()
    {
        $concepts = Concept::query()
            ->when($this->search, function ($query) {
                $term = '%' . $this->search . '%';
                $query->where('name', 'like', $term);
            })
            ->orderBy('name')
            ->paginate($this->perPage);
        return view('livewire.concepts.index', [
            'concepts' => $concepts,
        ]);
    }
}
