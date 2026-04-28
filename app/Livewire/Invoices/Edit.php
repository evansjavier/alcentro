<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Edit extends Component
{
    public Invoice $invoice;
    public array $items = [];

    public function mount(Invoice $invoice)
    {
        if ($invoice->document_status !== Invoice::DOC_STATUS_DRAFT) {
            abort(403, 'Solo se pueden editar facturas en borrador.');
        }

        $this->invoice = $invoice->load(['client', 'contract.premise', 'items']);
        
        foreach ($this->invoice->items as $item) {
            $this->items[$item->id] = [
                'description' => $item->description,
                'amount' => (float) $item->amount,
            ];
        }
    }

    public function save()
    {
        $this->validate([
            'items.*.description' => 'required|string|max:255',
            'items.*.amount' => 'required|numeric|min:0',
        ]);

        $totalAmount = 0;

        foreach ($this->items as $id => $data) {
            $item = $this->invoice->items()->find($id);
            if ($item) {
                $item->update([
                    'description' => $data['description'],
                    'amount' => $data['amount'],
                ]);
                $totalAmount += $data['amount'];
            }
        }

        $this->invoice->update([
            'total_amount' => $totalAmount,
        ]);
        
        $this->invoice->recalculateStatus();

        session()->flash('status', 'Factura actualizada exitosamente.');

        return redirect()->route('invoices.show', $this->invoice);
    }

    public function render()
    {
        return view('livewire.invoices.edit');
    }
}
