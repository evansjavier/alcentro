<div>
    @section("title", __("Pagos Recibidos"))

    <div class="flex items-center justify-between gap-2.5 flex-wrap mb-7.5">
        <div class="flex flex-col">
            <h3 class="text-base text-mono font-medium">Mostrando {{ $payments->total() }} pagos</h3>
            <span class="text-sm text-secondary-foreground">{{ $payments->firstItem() ?? 0 }}-{{ $payments->lastItem() ?? 0 }} de {{ $payments->total() }}</span>
        </div>
        <div class="flex items-center gap-2">
            @if(count($selectedPayments) > 0 && auth()->user()->hasRole(\App\Models\Role::ROLE_OWNER))
                <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'approval-modal')" class="kt-btn kt-btn-success kt-btn-sm font-semibold">
                    <i class="ki-filled ki-check-circle"></i> Aprobar Seleccionados ({{ count($selectedPayments) }})
                </button>
            @endif
            <!-- Espacio para botones -->
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4 border border-green-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="ki-filled ki-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="flex items-center flex-wrap gap-2.5 mb-4">
        <select wire:model.live="status" class="kt-input w-44">
            <option value="all">Todos los estados</option>
            <option value="approved">Aprobados</option>
            <option value="pending">Pendientes</option>
        </select>

        <select wire:model.live="method" class="kt-input w-44">
            <option value="all">Cualquier método</option>
            <option value="cash">Efectivo</option>
            <option value="wire_transfer">Transferencia / Depósito</option>
        </select>

        <div class="flex">
            <label class="kt-input text-secondary-foreground flex items-center">
                <i class="ki-filled ki-magnifier mr-3"></i>
                <input wire:model.live.debounce.300ms="search" placeholder="Cliente, periodo o ref..." type="search" class="w-64 border-0 focus:ring-0 p-0 !bg-transparent" />
            </label>
        </div>
    </div>

    <div class="kt-card">
        <div class="kt-card-content p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left bg-muted/60">
                        <tr class="text-secondary-foreground">
                            <th class="px-4 py-3 w-10">
                                @if(auth()->user()->hasRole(\App\Models\Role::ROLE_OWNER))
                                    <input type="checkbox" wire:model.live="selectAll" class="kt-checkbox">
                                @endif
                            </th>
                            <th class="px-4 py-3 font-medium">Fecha</th>
                            <th class="px-4 py-3 font-medium">Factura / Periodo</th>
                            <th class="px-4 py-3 font-medium">Cliente</th>
                            <th class="px-4 py-3 font-medium">Método / Ref</th>
                            <th class="px-4 py-3 font-medium">Monto</th>
                            <th class="px-4 py-3 font-medium">Estado</th>
                            <th class="px-4 py-3 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-input">
                        @forelse ($payments as $payment)
                            <tr class="{{ in_array($payment->id, $selectedPayments) ? 'bg-primary/5' : '' }}">
                                <td class="px-4 py-3">
                                    @if(!$payment->is_approved && auth()->user()->hasRole(\App\Models\Role::ROLE_OWNER))
                                        <input type="checkbox" wire:model.live="selectedPayments" value="{{ $payment->id }}" class="kt-checkbox">
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $payment->payment_date->format("d/m/Y") }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-mono">#{{ str_pad($payment->invoice_id, 6, "0", STR_PAD_LEFT) }}</div>
                                    <div class="text-xs text-muted-foreground">{{ $payment->invoice->period }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium">{{ $payment->invoice->client->name }}</div>
                                    <div class="text-xs text-muted-foreground">{{ $payment->invoice->client->tax_id }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1.5 py-1 px-2 rounded-md text-xs font-medium bg-muted text-secondary-foreground border border-input">
                                        {{ $payment->method === "cash" ? "Efectivo" : "Transferencia" }}
                                    </span>
                                    @if($payment->reference_number)
                                        <div class="text-xs text-muted-foreground mt-1 text-mono">Ref: {{ $payment->reference_number }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-semibold text-green-600">${{ number_format((float) $payment->amount_received, 2, ".", ",") }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="kt-badge kt-badge-outline px-2.5 py-1 text-xs font-medium rounded-full {{ $payment->is_approved ? 'bg-green-500/10 text-green-700 border-green-200' : 'bg-yellow-500/10 text-yellow-700 border-yellow-200' }}"
                                    >
                                        {{ $payment->is_approved ? 'Aprobado' : 'Pendiente' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="kt-menu justify-end" data-kt-menu="true">
                                        <div class="kt-menu-item kt-menu-item-dropdown" data-kt-menu-item-offset="0, 10px" data-kt-menu-item-placement="bottom-end" data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click">
                                            <button class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                                                <i class="ki-filled ki-dots-vertical text-lg"></i>
                                            </button>
                                            <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px] text-left" data-kt-menu-dismiss="true">
                                                @if(!$payment->is_approved)
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="{{ route('payments.edit', $payment) }}">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-pencil"></i>
                                                            </span>
                                                            <span class="kt-menu-title">Editar Pago</span>
                                                        </a>
                                                    </div>
                                                @endif
                                                <div class="kt-menu-item">
                                                    <a class="kt-menu-link" href="{{ route('payments.show', $payment) }}">
                                                        <span class="kt-menu-icon">
                                                            <i class="ki-filled ki-search-list"></i>
                                                        </span>
                                                        <span class="kt-menu-title">Ver Detalles</span>
                                                    </a>
                                                </div>
                                                <div class="kt-menu-separator"></div>
                                                <div class="kt-menu-item">
                                                    <a class="kt-menu-link" href="{{ route('invoices.show', $payment->invoice_id) }}">
                                                        <span class="kt-menu-icon">
                                                            <i class="ki-filled ki-bill"></i>
                                                        </span>
                                                        <span class="kt-menu-title">Ver Factura</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-6 text-center text-secondary-foreground" colspan="8">No hay pagos registrados que coincidan con la búsqueda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="p-4">
            {{ $payments->onEachSide(1)->links() }}
        </div>
    </div>

    <!-- Modal de confirmación -->
    <x-modal name="approval-modal" maxWidth="md">
        <div class="">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-mono">Confirmar Aprobación Múltiple</h3>
                <button type="button" @click="$dispatch('close-modal', 'approval-modal')" class="text-secondary-foreground hover:text-primary transition-colors">
                    <i class="ki-filled ki-cross text-xl"></i>
                </button>
            </div>

            <div class="kt-modal-body space-y-4 p-0 pr-1 pb-2">
                <p class="text-muted-foreground text-sm">
                    ¿Estás seguro de que deseas aprobar <strong class="text-foreground">{{ count($selectedPayments) }}</strong> pago(s) simultáneamente?
                </p>
                <div class="bg-yellow-50 text-yellow-800 p-3 rounded text-xs flex gap-2">
                    <i class="ki-filled ki-warning text-base mt-0.5"></i>
                    <span>Una vez aprobados, estos pagos ya no podrán editarse y se sumarán al saldo pagado de cada factura correspondiente.</span>
                </div>
            </div>

            <div class="mt-5 pt-5 border-t border-input flex flex-wrap items-center justify-end gap-3">
                <button type="button" @click="$dispatch('close-modal', 'approval-modal')" class="kt-btn kt-btn-outline text-secondary-foreground">Cancelar</button>
                <button wire:click="approveSelected" wire:loading.attr="disabled" class="kt-btn kt-btn-success">
                    <span wire:loading.remove wire:target="approveSelected">Sí, Aprobar Todos</span>
                    <span wire:loading wire:target="approveSelected" style="display: none;">
                        <i class="ki-filled ki-loading animate-spin mr-2"></i> Procesando
                    </span>
                </button>
            </div>
        </div>
    </x-modal>
</div>

