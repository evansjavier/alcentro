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

    public function render()
    {
        return view('livewire.expenses.show');
    }
}
