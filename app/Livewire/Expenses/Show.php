<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Expense $expense;

    public function mount(Expense $expense)
    {
        $this->expense = $expense->load('concept', 'user');
    }

    public function downloadAttachment()
    {
        if (!$this->expense->attachment_path) {
            return null;
        }

        return response()->download(storage_path('app/public/' . $this->expense->attachment_path));
    }

    public function approveExpense()
    {
        if (!auth()->user()->hasRole(\App\Models\Role::ROLE_OWNER)) {
            abort(403, 'No tienes permisos para aprobar este gasto.');
        }

        if ($this->expense->is_approved) {
            return;
        }

        $this->expense->update([
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        session()->flash('status', 'Gasto aprobado exitosamente.');
        $this->dispatch('close-modal', 'approval-modal');
    }

    public function render()
    {
        return view('livewire.expenses.show');
    }
}
