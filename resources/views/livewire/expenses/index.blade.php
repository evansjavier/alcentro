<div>
    @section('title', __('Egresos'))

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

    <div class="flex items-center justify-between gap-2.5 flex-wrap mb-7.5">
        <div class="flex flex-col">
            <h3 class="text-base text-mono font-medium">Mostrando {{ $expenses->total() }} gastos</h3>
            <span class="text-sm text-secondary-foreground">{{ $expenses->firstItem() ?? 0 }}-{{ $expenses->lastItem() ?? 0 }} de {{ $expenses->total() }}</span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('expense_concepts.index') }}" class="kt-btn kt-btn-outline kt-btn-sm text-secondary-foreground">
                <i class="ki-outline ki-setting-2"></i> Administrar Conceptos
            </a>
            <a class="kt-btn kt-btn-primary kt-btn-sm" href="{{ route('expenses.create') }}">
                <i class="ki-outline ki-plus"></i> Registrar Gasto
            </a>
        </div>
    </div>

    <div class="flex items-center flex-wrap gap-2.5 mb-4">

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
                            <th class="px-4 py-3 font-medium">Fecha</th>
                            <th class="px-4 py-3 font-medium">Concepto</th>
                            <th class="px-4 py-3 font-medium">Referencia</th>
                            <th class="px-4 py-3 font-medium">Método Pago</th>
                            <th class="px-4 py-3 font-medium">Monto Total</th>
                            <th class="px-4 py-3 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-input">
                        @forelse ($expenses as $expense)
                            <tr>
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
                                <td class="px-4 py-3 text-right">
                                    <a class="kt-btn kt-btn-light kt-btn-sm" href="{{ route('expenses.show', $expense) }}">
                                        Ver detalle
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-6 text-center text-secondary-foreground" colspan="6">
                                    No hay gastos registrados con los filtros actuales.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="p-4">
            {{ $expenses->onEachSide(1)->links() }}
        </div>
    </div>
</div>
