<div>
    @section('title', __('Facturas'))

    <div class="flex items-center justify-between gap-2.5 flex-wrap mb-7.5">
        <div class="flex flex-col">
            <h3 class="text-base text-mono font-medium">Mostrando {{ $invoices->total() }} facturas</h3>
            <span class="text-sm text-secondary-foreground">{{ $invoices->firstItem() ?? 0 }}-{{ $invoices->lastItem() ?? 0 }} de {{ $invoices->total() }}</span>
        </div>
        <div class="flex items-center gap-2">
            <!-- Espacio para futuros botones -->
        </div>
    </div>

    <div class="flex items-center flex-wrap gap-2.5 mb-4">
        <select wire:model.live="status" class="kt-input w-44">
            <option value="all">Cualquier estado</option>
            <option value="pending">Pendiente</option>
            <option value="partial">Abono parcial</option>
            <option value="paid">Pagada</option>
        </select>

        <select wire:model.live="sort" class="kt-input w-44">
            <option value="latest">Vencimiento: Más lejanas</option>
            <option value="oldest">Vencimiento: Más cercanas</option>
        </select>

        <div class="flex">
            <label class="kt-input">
                <i class="ki-filled ki-magnifier"></i>
                <input wire:model.live.debounce.300ms="search" placeholder="Buscar por periodo o cliente" type="search" class="w-64" />
            </label>
        </div>
    </div>

    <div class="kt-card">
        <div class="kt-card-content p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left bg-muted/60">
                        <tr class="text-secondary-foreground">
                            <th class="px-4 py-3 font-medium">Periodo</th>
                            <th class="px-4 py-3 font-medium">Cliente / Empresa</th>
                            <th class="px-4 py-3 font-medium">Monto Total</th>
                            <th class="px-4 py-3 font-medium">Vencimiento</th>
                            <th class="px-4 py-3 font-medium">Estado</th>
                            <th class="px-4 py-3 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-input">
                        @forelse ($invoices as $invoice)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-mono">{{ $invoice->period }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $invoice->client?->name ?? '—' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 font-medium">${{ number_format((float) $invoice->total_amount, 2, '.', ',') }}</td>
                                <td class="px-4 py-3">{{ $invoice->due_date?->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusLabel = [
                                            'pending' => 'Pendiente',
                                            'partial' => 'Abono Parcial',
                                            'paid' => 'Pagada',
                                        ][$invoice->status] ?? $invoice->status;
                                        $statusColor = match ($invoice->status) {
                                            'pending' => 'bg-amber-500/10 text-amber-700 border-amber-200',
                                            'partial' => 'bg-blue-500/10 text-blue-700 border-blue-200',
                                            'paid' => 'bg-green-500/10 text-green-700 border-green-200',
                                            default => 'bg-muted text-foreground border-input',
                                        };
                                    @endphp
                                    <span class="kt-badge kt-badge-outline {{ $statusColor }} border px-2.5 py-1 text-xs font-medium rounded-full">{{ $statusLabel }}</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a class="kt-btn kt-btn-light kt-btn-sm" href="{{ route('invoices.show', $invoice) }}">Ver factura</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-6 text-center text-secondary-foreground" colspan="6">No hay facturas registradas con los filtros actuales.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="p-4">
            {{ $invoices->onEachSide(1)->links() }}
        </div>
    </div>
</div>
