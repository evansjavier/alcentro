<div>
    @section('title', __('Editar factura'))

    <form wire:submit="save" class="grid gap-5 lg:gap-7.5 xl:w-[48rem] mx-auto">
        <div class="flex items-center justify-between gap-2">
            <a class="kt-btn kt-btn-light" href="{{ route('invoices.index') }}">
                <i class="ki-filled ki-arrow-left"></i>
                Volver
            </a>
            
            <button type="submit" class="kt-btn kt-btn-primary">
                <i class="ki-filled ki-check"></i>
                Guardar Cambios
            </button>
        </div>

        <div class="kt-card">
            <div class="kt-card-header">
                <h3 class="kt-card-title flex items-center gap-2">
                    Factura #{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}
                    <span class="kt-badge kt-badge-outline bg-gray-100 text-gray-700 border-gray-200 px-2.5 py-1 text-xs font-bold uppercase rounded-full ml-2 w-max">Borrador</span>
                </h3>
            </div>
            <div class="kt-card-content">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 pb-6 border-b border-input">
                    <div>
                        <span class="text-sm text-secondary-foreground block mb-1">Empresa / Inquilino</span>
                        <div class="font-semibold">{{ $invoice->client?->name ?? '—' }}</div>
                        <div class="text-sm text-muted-foreground">{{ $invoice->client?->tax_id }}</div>
                    </div>
                    <div>
                        <span class="text-sm text-secondary-foreground block mb-1">Local</span>
                        @if($invoice->contract?->premise)
                            <div class="font-semibold">{{ $invoice->contract->premise->code }}</div>
                            <div class="text-sm text-muted-foreground">Contrato #{{ $invoice->contract->id }}</div>
                        @else
                            <div class="font-semibold">—</div>
                        @endif
                    </div>
                    <div>
                        <span class="text-sm text-secondary-foreground block mb-1">Periodo</span>
                        <div class="font-medium">{{ $invoice->period }}</div>
                        <div class="text-sm text-muted-foreground mt-1">Vence: {{ $invoice->due_date?->format('d/m/Y') }}</div>
                    </div>
                </div>

                <div class="mb-8">
                    <h4 class="text-sm font-semibold my-3">Detalle de conceptos facturados</h4>
                    <div class="border border-input rounded-lg overflow-hidden">
                        <table class="min-w-full text-sm">
                            <thead class="bg-muted/60">
                                <tr class="text-left text-secondary-foreground">
                                    <th class="px-4 py-2 font-medium">Concepto</th>
                                    <th class="px-4 py-2 font-medium text-right">Monto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-input">
                                @foreach($invoice->items as $item)
                                    <tr>
                                        <td class="px-4 py-3 align-top">
                                            <input type="text" wire:model="items.{{ $item->id }}.description" class="kt-input w-full" required />
                                            @error('items.'.$item->id.'.description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                            <div class="text-xs text-muted-foreground mt-1">Tipo: {{ $item->type_label }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-right font-medium align-top">
                                            <div class="flex items-center gap-1 justify-end">
                                                <span class="text-muted-foreground">$</span>
                                                <input type="number" step="0.01" wire:model="items.{{ $item->id }}.amount" class="kt-input w-36 text-right" required />
                                            </div>
                                            @error('items.'.$item->id.'.amount') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
