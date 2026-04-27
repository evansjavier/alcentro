<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use App\Models\Concept;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Create extends Component
{
    use WithFileUploads;

    public $concept_id = '';
    public $amount = '';
    public $expense_date = '';
    public $payment_method = 'transferencia';
    public $reference_number = '';
    public $notes = '';
    public $attachment;

    public function mount()
    {
        $this->expense_date = date('Y-m-d');
    }

    protected $rules = [
        'concept_id' => 'required|exists:concepts,id',
        'amount' => 'required|numeric|min:0.01',
        'expense_date' => 'required|date',
        'payment_method' => 'required|string',
        'reference_number' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
        'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
    ];

    public function save()
    {
        $this->validate();

        $path = null;
        if ($this->attachment) {
            $path = $this->attachment->store('expenses_attachments', 'public');
        }

        Expense::create([
            'concept_id' => $this->concept_id,
            'amount' => $this->amount,
            'expense_date' => $this->expense_date,
            'payment_method' => $this->payment_method,
            'reference_number' => $this->reference_number,
            'notes' => $this->notes,
            'attachment_path' => $path,
            'user_id' => Auth::id(),
        ]);

        session()->flash('success', 'El gasto fue registrado correctamente.');
        return $this->redirectRoute('expenses.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.expenses.create', [
            'concepts' => Concept::where('is_active', true)->orderBy('name')->get(),
            'paymentMethods' => Expense::$paymentMethods,
        ]);
    }
}
