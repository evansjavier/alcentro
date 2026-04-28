<div>
    @section('title', __('Gastos'))

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4 border border-green-200">
            <div class="flex">
                <div class="flex-shrink-0 pr-2">
                    <i class="ki-filled ki-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="flex items-center justify-between gap-2.5 flex-wrap mb-7.5">
        <div class="flex flex-col">
            <h3 class="text-base text-mono font-medium">Mostrando {{ $expenses->total() }} gastos</h3>
            <span class="text-sm text-secondary-foreground">{{ $expenses->firstItem() ?? 0 }}-{{ $expenses->lastItem() ?? 0 }} de {{ $expenses->total() }}</span>
        </div>
        <div class="flex items-center gap-2">
            @if(count($selectedExpenses) > 0 && auth()->user()->hasRole(\App\Models\Role::ROLE_OWNER))
                <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'approval-modal')" class="kt-btn kt-btn-success kt-btn-sm font-semibold">
                    <i class="ki-filled ki-check-circle"></i> Aprobar Seleccionados ({{ count($selectedExpenses) }})
                </button>
            @endif
            <a href="{{ route('concepts.index') }}" class="kt-btn kt-btn-outline kt-btn-sm text-secondary-foreground">
                <i class="ki-outline ki-setting-2"></i> Administrar Conceptos
            </a>
            <a class="kt-btn kt-btn-primary kt-btn-sm" href="{{ route('expenses.create') }}">
                <i class="ki-outline ki-plus"></i> Registrar Gasto
            </a>
        </div>
    </div>

    <div class="flex items-center flex-wrap gap-2.5 mb-4">

        <select wire:model.live="status" class="kt-input w-44">
            <option value="all">Todos los estados</option>
            <option value="approved">Aprobados</option>
            <option value="pending">Pendientes</option>
        </select>

        <div class="flex">
            <label class="kt-input">
                <i class="ki-filled ki-magnifier"></i>
                <input wire:model.live.debounce.500ms="search" placeholder="Buscar por referencia o notas..." type="search" class="w-64" />
            </label>
        </div>

        <select wire:model.live="concept_id" class="kt-input w-44">
            <option value="">Todos los conceptos</option>
            @foreach($concepts as $concept)
                <option value="{{ $concept->id }}">{{ $concept->name }}</option>
            @endforeach
        </select>

        <div class="flex items-center bg-white border border-input rounded-md px-3 py-1.5 h-10 gap-2">
            <span class="text-sm text-muted-foreground">Desde</span>
            <input type="date" wire:model.live="date_from" class="bg-transparent text-sm border-none focus:ring-0 p-0 text-foreground w-auto outline-none" />
        </div>

        <div class="flex items-center bg-white border border-input rounded-md px-3 py-1.5 h-10 gap-2">
            <span class="text-sm text-muted-foreground">Hasta</span>
            <input type="date" wire:model.live="date_to" class="bg-transparent text-sm border-none focus:ring-0 p-0 text-foreground w-auto outline-none" />
        </div>

        @if($search || $concept_id || $date_from || $date_to)
            <button class="kt-btn kt-btn-light kt-btn-icon" wire:click="clearFilters" title="Limpiar filtros">
                <i class="ki-outline ki-cross"></i>
            </button>
        @endif
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
                            <th class="px-4 py-3 font-medium">Concepto</th>
                            <th class="px-4 py-3 font-medium">Referencia</th>
                            <th class="px-4 py-3 font-medium">Método Pago</th>
                            <th class="px-4 py-3 font-medium">Monto Total</th>
                            <th class="px-4 py-3 font-medium">Estado</th>
                            <th class="px-4 py-3 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-input">
                        @forelse ($expenses as $expense)
                            <tr class="{{ in_array($expense->id, $selectedExpenses) ? 'bg-primary/5' : '' }}">
                                <td class="px-4 py-3">
                                    @if(!$expense->is_approved && auth()->user()->hasRole(\App\Models\Role::ROLE_OWNER))
                                        <input type="checkbox" wire:model.live="selectedExpenses" value="{{ $expense->id }}" class="kt-checkbox">
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-semibold text-mono">
                                    {{ $expense->expense_date->format('Y-m-d') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $expense->concept->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-secondary-foreground">{{ $expense->reference_number ?: '—' }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="kt-badge kt-badge-outline bg-muted text-foreground border-input border px-2.5 py-1 text-xs font-medium rounded-full">
                                        {{ \App\Models\Expense::$paymentMethods[$expense->payment_method] ?? $expense->payment_method }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-medium">${{ number_format((float) $expense->amount, 2, '.', ',') }}</td>
                                <td class="px-4 py-3">
                                    @if($expense->is_approved)
                                        <span class="kt-badge kt-badge-outline bg-green-500/10 text-green-700 border-green-200 border px-2.5 py-1 text-xs font-medium rounded-full">Aprobado</span>
                                    @else
                                        <span class="kt-badge kt-badge-outline bg-yellow-500/10 text-yellow-700 border-yellow-200 border px-2.5 py-1 text-xs font-medium rounded-full">Pendiente</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a class="kt-btn kt-btn-light kt-btn-sm" href="{{ route('expenses.show', $expense) }}">
                                        Ver detalle
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-6 text-center text-secondary-foreground" colspan="8">
                                    No hay gastos registrados con los filtros actuales.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($expenses->count() > 0)
                        <tfoot class="bg-muted/40 border-t border-input">
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-right font-medium text-secondary-foreground uppercase tracking-wider text-xs">
                                    Total Gastos:
                                </td>
                                <td class="px-4 py-4 font-semibold text-base text-foreground">
                                    ${{ number_format((float) $totalSum, 2, '.', ',') }}
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
        <div class="p-4">
            {{ $expenses->onEachSide(1)->links() }}
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
                    ¿Estás seguro de que deseas aprobar <strong class="text-foreground">{{ count($selectedExpenses) }}</strong> gasto(s) simultáneamente?
                </p>
                <div class="bg-yellow-50 text-yellow-800 p-3 rounded text-xs flex gap-2">
                    <i class="ki-filled ki-warning text-base mt-0.5"></i>
                    <span>Una vez aprobados, estos gastos no podrán revertirse ni eliminarse de la contabilidad aprobada.</span>
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
