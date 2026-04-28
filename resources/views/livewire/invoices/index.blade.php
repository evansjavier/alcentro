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
                            <th class="px-4 py-3 font-medium">Local</th>
                            <th class="px-4 py-3 font-medium">Monto Total</th>
                            <th class="px-4 py-3 font-medium">Vencimiento</th>
                            <th class="px-4 py-3 font-medium">Estatus</th>
                            <th class="px-4 py-3 font-medium">Pago</th>
                            <th class="px-4 py-3 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-input">
                        @forelse ($invoices as $invoice)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-mono">
                                    {{ $invoice->period }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $invoice->client?->name ?? '—' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($invoice->contract?->premise)
                                        <span class="font-medium">Local {{ $invoice->contract->premise->code }}</span>
                                    @else
                                        <span class="text-secondary-foreground">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-medium">${{ number_format((float) $invoice->total_amount, 2, '.', ',') }}</td>
                                <td class="px-4 py-3">{{ $invoice->due_date?->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $docStatusLabel = $invoice->print_document_status;
                                        $docStatusColor = match ($invoice->document_status) {
                                            'draft' => 'kt-badge-secondary',
                                            'issued' => 'kt-badge-primary',
                                            'cancelled' => 'kt-badge-danger',
                                            default => 'kt-badge-secondary',
                                        };
                                    @endphp
                                    <span class="kt-badge kt-badge-outline px-2.5 py-1 text-xs font-medium rounded-full {{ $docStatusColor }}">{{ $docStatusLabel }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusLabel = [
                                            'pending' => 'Pendiente',
                                            'partial' => 'Abono Parcial',
                                            'paid' => 'Pagada',
                                        ][$invoice->status] ?? $invoice->status;
                                        $statusColor = match ($invoice->status) {
                                            'pending' => 'kt-badge-warning',
                                            'partial' => 'kt-badge-info',
                                            'paid' => 'kt-badge-success',
                                            default => 'kt-badge-secondary',
                                        };
                                    @endphp
                                    <span class="kt-badge kt-badge-outline px-2.5 py-1 text-xs font-medium rounded-full {{ $statusColor }}">{{ $statusLabel }}</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($invoice->document_status === 'draft')
                                            <button wire:click="confirmApprove({{ $invoice->id }})" class="kt-btn kt-btn-success kt-btn-sm" title="Aprobar Factura">
                                                <i class="ki-filled ki-check-circle"></i> Aprobar
                                            </button>
                                            <a class="kt-btn kt-btn-light kt-btn-sm kt-btn-icon" href="{{ route('invoices.edit', $invoice) }}" title="Editar factura">
                                                <i class="ki-filled ki-pencil"></i>
                                            </a>
                                        @endif
                                        <a class="kt-btn kt-btn-light kt-btn-sm" href="{{ route('invoices.show', $invoice) }}" title="Ver factura">
                                            <i class="ki-filled ki-eye"></i> Ver
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-6 text-center text-secondary-foreground" colspan="8">No hay facturas registradas con los filtros actuales.</td>
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

    <x-modal name="approval-modal" maxWidth="md">
        <div class="">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-mono">Confirmar Aprobación</h3>
                <button type="button" @click="$dispatch('close-modal', 'approval-modal')" class="text-secondary-foreground hover:text-primary transition-colors">
                    <i class="ki-filled ki-cross text-xl"></i>
                </button>
            </div>

            <div class="kt-modal-body space-y-4 p-0 pr-1 pb-2">
                <p class="text-muted-foreground text-sm">
                    ¿Estás seguro de que deseas aprobar esta factura?
                </p>
                <div class="bg-yellow-50 text-yellow-800 p-3 rounded text-xs flex gap-2">
                    <i class="ki-filled ki-warning text-base mt-0.5"></i>
                    <span>Una vez aprobada y emitida, los conceptos y montos de esta factura ya no podrán ser editados.</span>
                </div>
            </div>

            <div class="mt-5 pt-5 border-t border-input flex flex-wrap items-center justify-end gap-3">
                <button type="button" @click="$dispatch('close-modal', 'approval-modal')" class="kt-btn kt-btn-outline text-secondary-foreground">Cancelar</button>
                <button wire:click="approve" wire:loading.attr="disabled" class="kt-btn kt-btn-success">
                    <span wire:loading.remove wire:target="approve">Sí, Aprobar Factura</span>
                    <span wire:loading wire:target="approve" style="display: none;">
                        <i class="ki-filled ki-loading animate-spin mr-2"></i> Procesando
                    </span>
                </button>
            </div>
        </div>
    </x-modal>
</div>
